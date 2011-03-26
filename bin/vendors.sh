#!/bin/sh

# Get root directory
cd $(dirname $0)/..
ROOT_PATH=$(pwd)
echo $ROOT_PATH
VENDOR_DIR='vendor'
VENDOR_PATH="$ROOT_PATH/$VENDOR_DIR"

# initialization
if [ "$1" = "--reinstall" -o "$2" = "--reinstall" ]; then
    rm -rf $VENDOR_PATH
    git submodule update --init
    exit 0;
fi

mkdir -p "$VENDOR_PATH" 

##
# @param destination directory (e.g. "doctrine")
# @param URL of the git remote (e.g. git://github.com/doctrine/doctrine2.git)
# @param hash or tag of a specific commit (e.g. "vPR8")
#
add_submodule()
{
    cd $ROOT_PATH

    INSTALL_DIR=$VENDOR_DIR/$1
    SOURCE_URL=$2
    HASH=$3
    
    if [ ! -d $INSTALL_DIR ]; then
        git submodule add $SOURCE_URL $INSTALL_DIR
        if [ -n $HASH ]; then
           cd $INSTALL_DIR
           git checkout $HASH
        fi
    fi
}

# Assetic
add_submodule assetic git://github.com/kriswallsmith/assetic.git

# Symfony
add_submodule symfony git://github.com/symfony/symfony.git

# Doctrine ORM
add_submodule doctrine git://github.com/doctrine/doctrine2.git

# Doctrine DBAL
add_submodule doctrine-dbal git://github.com/doctrine/dbal.git

# Doctrine Common
add_submodule doctrine-common git://github.com/doctrine/common.git

# Swiftmailer
add_submodule swiftmailer git://github.com/swiftmailer/swiftmailer.git

# Twig
add_submodule twig git://github.com/fabpot/Twig.git

# Twig Extensions
add_submodule twig-extensions git://github.com/fabpot/Twig-extensions.git

# Zend Framework Log
add_submodule zend-log/Zend/Log git://github.com/symfony/zend-log.git

# FrameworkExtraBundle
add_submodule bundles/Sensio/Bundle/FrameworkExtraBundle git://github.com/sensio/SensioFrameworkExtraBundle.git

# SecurityExtraBundle
add_submodule bundles/JMS/SecurityExtraBundle git://github.com/schmittjoh/SecurityExtraBundle.git

