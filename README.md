Sculpin based User-Documentation for Graviton
==================================

Powered by [Sculpin](http://sculpin.io). =)

Features
--------

Sculpin based Documentation.

 * Very minimal comstrap based theme.
 * Utilizes sculpin-pages-bundle to build our documentation from content in ``source/_pages``.
 * Split navigation (top and left) that allows us to keep documentation concerns somewhat separate.
 * Contains high-level API docs, some guides and ops info.

Build
-----

### If You Use Composer

    composer install
    vendor/bin/sculpin generate --watch --server

Your newly generated clone of sculpin-blog-skeleton is now
accessible at `http://localhost:8000/`.

### If You Already Have Sculpin

    sculpin install
    sculpin generate --watch --server

### If You Need Sculpin

    curl -O https://download.sculpin.io/sculpin.phar
    php sculpin.phar install
    php sculpin.phar generate --watch --server


Previewing Development Builds
-----------------------------

By default the site will be generated in `output_dev/`. This is the location
of your development build.

To preview it with Sculpin's built in webserver, run either of the following
commands. This will start a simple webserver listening at `localhost:8000`.

### Using Sculpin's Internal Webserver

#### Generate Command

To serve files right after generating them, use the `generate` command with
the `--server` option:

    sculpin generate --server

To listen on a different port, specify the `--port` option:

    sculpin generate --server --port=9999

Combine with `--watch` to have Sculpin pick up changes as you make them:

    sculpin generate --server --watch


##### Server Command

To serve files that have already been generated, use the `serve` command:

    sculpin serve

To listen on a different port, specify the `--port` option:

    sculpin serve --port=9999


### Using a Standard Webserver

The only special consideration that needs to be taken into account for standard
webservers **in development** is the fact that the URLs generated may not match
the path at which the site is installed.

This can be solved by overriding the `site.url` configuration option when
generating the site.

    sculpin generate --url=http://my.dev.host/blog-skeleton/output_dev

With this option passed, `{{ site.url }}/about` will now be generated as
`http://my.dev.host/blog-skelton/output_dev/about` instead of `/about`.


Publishing Production Builds
----------------------------

### Deploy To CloudFoundry

    composer build
    vendor/bin/deploy g:d:c:d grv-releasenotes

### Manually

When `--env=prod` is specified, the site will be generated in `output_prod/`. This
is the location of your production build.

    sculpin generate --env=prod

These files are suitable to be transferred directly to a production host. For example:

    sculpin generate --env=prod
    rsync -avze 'ssh -p 999' output_prod/ user@yoursculpinsite.com:public_html

If you want to make sure that rsync deletes files that you deleted locally on the on the remote too, add the `--delete` option to the rsync command:

    rsync -avze 'ssh -p 999' --delete output_prod/ user@yoursculpinsite.com:public_html
    
Additional Info
---------------

### Use of pictograms
Add a pictogram name from [here](http://swisscom-bootstrap.nova.scapp.io/index.html) to the title:

`title: sampleTitle$pictoName`


### Marking pages as outdated

You can use ``outdated: true`` to mark pages in need of a refactor. This will then render a Warning banner on the top of the page.
