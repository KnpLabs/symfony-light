<?php

namespace Knp\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\Config\Resource\FileResource;
use Knp\Component\Config\GlobLocator;

/**
 * Modifies template finder to find root templates in src folder
 *
 * @author     Florian Klein <florian.klein@free.fr>
 */
class AddValidationFilesPass implements CompilerPassInterface
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

        $locator = new GlobLocator($container->getParameter('kernel.root_dir').'/../src/validation', false);
        $newPaths = $locator->locate('*.yml');
        foreach ($newPaths as $file) {
            $container->addResource(new FileResource($file));
        }

        $paths = array_merge($paths, $newPaths);

        return $paths;
    }
}

