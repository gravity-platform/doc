---
title: Installation Guide
layout: default
---
# How to Install the Gravity-Platform

Ensure that your system meets the following dependencies.

* php >= 5.3
* npm
* git

## OS X Mavericks environment preparation

On OS X Mavericks you can follow the next few steps.

If you are not on Mavericks this will probably also work. You will
need to use a different zend_extension path in the ini file below
and you might miss out on PHP 5.4 features on older OSX.

To install npm and git you need a package manager like [MacPorts](http://www.macports.org/).

After installing MacPorts and adding ``/usr/local/bin`` to PATH you can install stuff.

The following prerequisites are needed to install pecl packages.

````bash
sudo port install autoconf # prereq for pecl packages
sudo port install icu      # prereq for mod_intl pecl package
````

````bash
sudo php -d detect_unicode=0 /usr/lib/php/install-pear-nozlib.phar
sudo pecl install xdebug
sudo pecl install intl
sudo pecl install mongo
sudo vim /etc/php.ini
````

Create the following php.ini file.

````ini
; extensions
zend_extension="/usr/lib/php/extensions/no-debug-non-zts-20100525/xdebug.so"
extension=intl.so
extension=mongo.so
; settings
include_path=".:/usr/lib/php/pear"
detect_unicode = Off
date.timezone="Europe/Zurich"
short_open_tag=off
magic_quotes_gpc=off
xdebug.max_nesting_level=250
````

Install npm if you need to hack graviphoton

````bash
sudo port install npm
sudo port install git
````

## Gravity-Platform Installation

This is the (currently defunct) all in one installation. Please refer to graviton
or graviphotons README.md for respective manual installation instructions.

Run the install commands.

````bash
git clone https://github.com/gravity-platform/gravity.git
curl -sS https://getcomposer.org/installer | php
php composer.phar install --dev
````

This might take a considerable amount of time since alot
of dependencies have to get cloned.

In the end you should have a working graviphoton in
````node_modules/graviphoton/dist/```` and a working
graviton in ````vendor/````.

# Keeping the Gravity-Platform Up-to-date.

Change to your ````gravity```` dir and run the following command.

````bash
php composer.php update
````
