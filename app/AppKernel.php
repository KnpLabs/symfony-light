<?php

use Knp\Component\Kernel\SmartKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends SmartKernel
{
    protected $rootDir = __DIR__;

    protected function getExcludedBundles()
    {
        $bundles = parent::getExcludedBundles();
        $bundles[] = 'Symfony/Bundle/DoctrineBundle';

        return $bundles;
    }

    protected function getExcludedBundlesByEnv()
    {
        $excluded = $this->getExcludedBundles();
        $excluded[] = 'Symfony/Bundle/WebProfilerBundle';
        $bundles['prod'] = $excluded;

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $basename = __DIR__ . '/config/config_' . $this->getEnvironment();

        if (file_exists($basename . '_local.yml')) {
            $loader->load($basename . '_local.yml');
        } else {
            $loader->load($basename . '.yml');
        }
    }
}
