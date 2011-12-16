<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * GlobRoutingLoader loads routing information from all routing files found wityh glob pattern
 *
 * @author Florian Klein <florian.klein@free.fr>
 */
class GlobRoutingLoader extends DelegatingLoader
{
    private $locator;

    public function __construct(ControllerNameParser $parser, GlobLocator $locator, LoaderResolverInterface $resolver)
    {
        parent::__construct($parser, null, $resolver);
        $this->locator = $locator;
    }

    /**
     * Loads from a directory glob pattern.
     *
     * @param array $glob a glob pattern
     * @param string $type The resource type
     *
     * @return RouteCollection A RouteCollection instance
     *
     * @throws \InvalidArgumentException When route can't be parsed
     */
    public function load($glob, $type = null)
    {
        $collection = new RouteCollection();
        foreach ($this->locator->locate($glob) as $path) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
                if (!$file->isFile() || false === strpos($file->getPathname(), 'routing')) {
                    continue;
                }

                if (parent::supports((string)$file)) {
                    $collection->addCollection(parent::load((string)$file));
                }
            }
        }

        return $collection;
    }

    /**
     * Returns true if this class supports the given resource, eg: if resource contains a "*" character
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && false !== strpos($resource, '*');
    }
}

