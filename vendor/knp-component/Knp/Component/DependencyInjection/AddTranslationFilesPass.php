<?php

namespace Knp\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Knp\Component\Config\GlobLocator;

/**
 * Modifies template finder to find root templates in src folder
 *
 * @author     Florian Klein <florian.klein@free.fr>
 */
class AddTranslationFilesPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('validator.mapping.loader.yaml_files_loader.mapping_files', $this->getPaths($container));
    }

    private function getPaths(ContainerBuilder $container)
    {
        $paths = $container->getParameter('validator.mapping.loader.yaml_files_loader.mapping_files');

        $locator = new GlobLocator($container->getParameter('kernel.root_dir').'/../src/translation', false);
        $newPaths = $locator->locate('*.*');
        foreach ($newPaths as $path) {
            $file = new \splFileInfo($path);
            list($domain, $locale, $format) = explode('.', $file->getBasename(), 3);
            $container->getDefinition('translator.default')
                ->addMethodCall('addResource', array($format, (string) $file, $locale, $domain));
        }

        $paths = array_merge($paths, $newPaths);

        return $paths;
    }
}
