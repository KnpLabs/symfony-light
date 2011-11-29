#!/bin/bash

file=$1
if [ -z "$file" ]; then
    file=".gitmodules.dist"
fi

for cfg in `git config -f $file -l`
do
    if [ -n "$path" ] && [ -z "$url" ]; then
        url=`echo $cfg | awk -F"submodule.*.url=" '{print $2}'`
    elif [ -n "$path" ] && [ -n "$url" ] && [ -z "$version" ]; then
        version=`echo $cfg | awk -F"submodule.*.version=" '{print $2}'`
    else
        path=`echo $cfg | awk -F"submodule.*.path=" '{print $2}'`
    fi

    if [ -n "$url" ] && [ -n "$path" ] && [ -n "$version" ]; then
        if [ -n "$2" ]; then
            git clone --depth $2 $url $path
        fi
        git submodule add $url $path

        if [ -n "$version" ]; then
            cd $path
            git checkout $version
            cd -
        fi

        url=""
        path=""
        version=""
    fi
done
