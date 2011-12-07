<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // core bundles
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            //new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            //new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            //new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            //new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),

            // extra bundles
            //new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

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
