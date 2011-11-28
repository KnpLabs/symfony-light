<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Config\ConfigCache;

/**
 * The SmartKernel class is able to register bundles
 * based on some conventions and some explicit lists
 * of include/exclude patterns
 **/
abstract class SmartKernel extends Kernel
{
    public function registerBundles()
    {
        // TODO handle cache
        $filename = sprintf('%s/%s/%s', __DIR__.'/cache/', $this->getEnvironment(), 'bundles.php');
        $cache = new ConfigCache($filename, $this->isDebug());
        if (!$cache->isFresh()) {
            $bundleNames = array();
            foreach ($this->getRegisterableBundles($this->getEnvironment()) as $bundle) {
                $bundleNames[] = sprintf('new %s', $bundle);
            }
            $content = sprintf('<?php $bundles = array(
                %s
            );', implode(", \n", $bundleNames));
            $cache->write($content);
        }
        require (string)$cache; // this defines a $bundles variable in this scope

        return $bundles;
    }

    protected function getRegisterableBundles($env)
    {
        $finder = new Finder;
        $finder
            ->files()
            ->name('*Bundle.php')
            ->exclude($this->getFinalExcludedBundles())
            ->in($this->getBundlesSearchDirs())
        ;

        $bundles = array();
        foreach ($finder as $file) {
            $bundle = $this->trim($file->getRealpath());
            $bundles[] = $bundle;
        }

        return $bundles;
    }

    protected function trim($path)
    {
        foreach ($this->getBundlesSearchDirs() as $dir) {
            $path = ltrim($path, realpath($dir));
        }
        $path = rtrim($path, '.php');
        $path = str_replace('/', '\\', $path);

        return $path;
    }

    protected function getExcludedBundles()
    {
        $bundles = array(
            'Symfony/Bundle/FrameworkBundle/Tests',
            'Symfony/Bundle/DoctrineBundle/Tests',
            'Symfony/Bundle/SecurityBundle/Tests',
            'Symfony/Component/HttpKernel/Bundle',
        );

        return $bundles;
    }

    protected function getExcludedBundlesByEnv()
    {
        return array();
    }

    protected function getFinalExcludedBundles()
    {
        $env = $this->getEnvironment();
        $bundles = $this->getExcludedBundlesByEnv();
        if( isset($bundles[$env])) {
            return $bundles[$env];
        }

        return $this->getExcludedBundles();
    }

    protected function getBundlesSearchDirs()
    {
        return array(
            __DIR__.'/../src',
            __DIR__.'/../vendor/symfony/src',
           // __DIR__.'/../src/vendor/bundles',
        );
    }
}
