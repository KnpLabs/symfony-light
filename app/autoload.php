<?php

require_once __DIR__.'/../vendor/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
    'Monolog'          => __DIR__.'/../vendor/monolog/src',
    //'Assetic'          => __DIR__.'/../vendor/assetic/src',
    //'Sensio'           => __DIR__.'/../vendor/bundles',
    //'JMS'              => __DIR__.'/../vendor/bundles',
    //'Doctrine\\Common' => __DIR__.'/../vendor/doctrine-common/lib',
    //'Doctrine\\DBAL'   => __DIR__.'/../vendor/doctrine-dbal/lib',
    //'Doctrine'         => __DIR__.'/../vendor/doctrine/lib',
));
$loader->registerPrefixes(array(
    //'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
    'Twig_'            => __DIR__.'/../vendor/twig/lib',
    //'Swift_'           => __DIR__.'/../vendor/swiftmailer/lib/classes',
));
$loader->register();

require_once __DIR__.'/GlobRoutingLoader.php';
require_once __DIR__.'/GlobLocator.php';
