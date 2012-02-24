<?php

namespace Knp\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Modifies template finder to find root templates in src folder
 *
 * @author     Florian Klein <florian.klein@free.fr>
 */
class ModifyRootResourcesPathPass implements CompilerPassInterface
{
     /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $path = $container->getParameterBag()->resolveValue('%kernel.root_dir%/../src');
        $container->getDefinition('templating.finder')->replaceArgument(2, $path);

        $container->getDefinition('file_locator')->replaceArgument(1, $path);
    }
}

