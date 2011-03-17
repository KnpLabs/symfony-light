Symfony Empty Edition
========================

What's inside?
--------------

Symfony Empty Edition comes pre-configured with the following bundles:

 * FrameworkBundle
 * SensioFrameworkExtraBundle
 * DoctrineBundle ( see dist files )
 * TwigBundle
 * SwiftmailerBundle ( see dist files )
 * ZendBundle
 * AsseticBundle
 * WebProfilerBundle (in dev/test env)

Installation from Git
---------------------

Run the following script:

 * `bin/vendors.sh`

 It will install all submodules

Configuration
-------------

The distribution is configured with the following defaults:

 * Twig is the only configured template engine;
 * Doctrine ORM/DBAL is configured;
 * Swiftmailer is configured;
 * Annotations for everything are enabled.

If you want to use the CLI, a console application is available at
`app/console`. Check first that your PHP is correctly configured for the CLI
by running `app/check.php`.

Enjoy!
