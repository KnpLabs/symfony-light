<?php

namespace Knp\Component\Kernel;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Config\ConfigCache;
use Knp\Component\DependencyInjection\ModifyRootResourcesPathPass;
use Knp\Component\DependencyInjection\AddTranslationFilesPass;
use Knp\Component\DependencyInjection\AddValidationFilesPass;
use Symfony\Component\HttpKernel\DependencyInjection\MergeExtensionConfigurationPass;
use Symfony\Component\HttpKernel\DependencyInjection\AddClassesToCachePass;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * The SmartKernel class is able to register bundles
 * based on some conventions and some explicit lists
 * of include/exclude patterns
 **/
abstract class SmartKernel extends Kernel
{
    public function registerBundles()
    {
        $filename = sprintf('%s/%s', $this->getCacheDir(), 'bundles.php');
        $cache = new ConfigCache($filename, $this->isDebug());
        if (!$cache->isFresh()) {
            $content = sprintf('<?php $bundles = array(
                %s
            );', implode(", \n", $this->getBundleNames()));
            $cache->write($content);
        }
        require (string)$cache; // this defines a "$bundles" variable in this scope

        return $bundles;
    }

    protected function getBundleNames()
    {
        $bundleNames = array();
        foreach ($this->getRegisterableBundles($this->getEnvironment()) as $bundle) {
            $bundleNames[] = sprintf('new %s($this)', $bundle);
        }

        return $bundleNames;
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
        return array_filter(array(
            $this->getRootDir().'/../src',
            $this->getRootDir().'/../vendor/symfony/src',
            $this->getRootDir().'/../vendor/bundles',
        ), function($dir) {
            return realpath($dir) !== false;
        });
    }

    /**
     * Builds the service container.
     *
     * @return ContainerBuilder The compiled service container
     */
    protected function getBuiltContainer()
    {
        foreach (array('cache' => $this->getCacheDir(), 'logs' => $this->getLogDir()) as $name => $dir) {
            if (!is_dir($dir)) {
                if (false === @mkdir($dir, 0777, true)) {
                    throw new \RuntimeException(sprintf("Unable to create the %s directory (%s)\n", $name, $dir));
                }
            } elseif (!is_writable($dir)) {
                throw new \RuntimeException(sprintf("Unable to write in the %s directory (%s)\n", $name, $dir));
            }
        }

        $container = new ContainerBuilder(new ParameterBag($this->getKernelParameters()));
        $extensions = array();
        foreach ($this->bundles as $bundle) {
            if ($extension = $bundle->getContainerExtension()) {
                $container->registerExtension($extension);
                $extensions[] = $extension->getAlias();
            }

            if ($this->debug) {
                $container->addObjectResource($bundle);
            }
        }
        foreach ($this->bundles as $bundle) {
            $bundle->build($container);
        }

        $container->addObjectResource($this);

        // ensure these extensions are implicitly loaded
        $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass($extensions));

        if (null !== $cont = $this->registerContainerConfiguration($this->getContainerLoader($container))) {
            $container->merge($cont);
        }

        return $container;
    }

    protected function buildContainer()
    {
        $container = $this->getBuiltContainer();

        $this->loadExtraServices($container);

        $container->addCompilerPass(new AddClassesToCachePass($this));
        $container->addCompilerPass(new ModifyRootResourcesPathPass($this));
        $container->addCompilerPass(new AddTranslationFilesPass($this));
        $container->addCompilerPass(new AddValidationFilesPass($this));

        $container->compile();

        return $container;
    }

    protected function loadExtraServices(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config'));
        $loader->load('services.yml');
    }
}
