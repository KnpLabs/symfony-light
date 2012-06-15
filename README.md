Symfony Light Edition
=====================

What's inside?
--------------

Symfony Light Edition comes pre-configured with the following bundles:

 * FrameworkBundle
 * MonologBundle
 * TwigBundle

Optionally:
 * SecurityBundle ( see security.yml.dist file )
 * SensioFrameworkExtraBundle
 * SecurityExtraBundle ( see config.yml.dist file )
 * DoctrineBundle ( see config.yml.dist file )
 * SwiftmailerBundle ( see config.yml.dist file )
 * AsseticBundle
 * WebProfilerBundle (in dev/test env)

Installation
------------

This distribution is made to be extracted in an empty git repository, in order to initiate a new Symfony2 project.
Nothing special, just extract and run it!

You can simply download it via `https://github.com/knplabs/symfony-light/tarball/master` and unpack it.

    wget --no-check-certificate https://github.com/knplabs/symfony-light/tarball/master -O symfony-light-master.tar.gz
    tar -xzvf symfony-light-master.tar.gz
    mv *-symfony-light-* my-project
    cd my-project

Vendors, or how to use submodules
---------------------------------

**This requires you to be in a working git repository, at root level.**
If you are not, you can init a new repository in the current folder by simply typing:

    git init

Run the following script:

 * `./bin/init-submodule.sh [.gitmodules.dist] [1]

   * [.gitmodules.dist] is a gitmodule file containing all needed vendors. If ommited, ``.gitmodules.dist`` is used
   * [1] is the git clone depth to limit history, and thus, filesystem size of vendors. If ommited, all history will be cloned

This script automates the creation of submodules, but you still can do it manually via:

    git submodule add <git url> vendor/<vendor name>


Last but not least, setup assets using:

    ./app/console assets:install web --symlink


Smart
-----

This provides a SmartKernel that autodetects installed bundles and registered them in a cachy way.

It also includes a GlobRoutingLoader that imports all routing files given a glob pattern:

    #routing.yml

    vendors:
        resource: ../vendor/bundles/FOS/*


Configuration
-------------

**You don't need to configure anything** by default, as the basic configuration file works out of the box.

The distribution is configured with the following defaults:

 * Twig is the only configured template engine;
 * Doctrine ORM/DBAL is deactivated ( see config.yml.dist file );
 * Swiftmailer is deactivated ( see config.yml.dist file );
 * Annotations for everything are enabled ( see config.yml.dist file ).


Custom config
-------------

If you have special configurations depending on your machine or environment, you can override any config_*.yml.

For example, if you have a different database password than the default one, you can modifiy it by adding a file named `config_dev_local.yml`:

    imports:
        - { resource: config_dev.yml }

    doctrine:
        dbal:
            connections:
                default:
                    dbname: my_symfony_light_special_db_name
                    user: florian
                    password: chaaaangeMe


If you want to use the CLI, a console application is available at
`app/console`. 

Enjoy!
