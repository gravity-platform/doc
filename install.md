---
title: Installation Guide
layout: default
---
# How to Install the Gravity-Platform

Ensure that you're system meets the following dependencies.

* php >= 5.3
* npm
* git

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
