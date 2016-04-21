# Generic Wordpress Theme Boilerplate for UCF Marketing

Stripped-down version of UCF's Generic Theme.  Provides a base set of
development tools, vanilla templates with semantic markup, and a starter
set of custom post types for rapid development.

Note that this theme does NOT come styled out-of-the-box; it is intended
strictly as a base for development of new themes.


## Installation Requirements:
- WordPress v4.1+

For development:
- node v0.10.22+
- gulp v3.9.0+


## Deployment

No special configuration should be necessary for deploying this theme. Static assets that require minification and/or concatenation are tracked in the repo and should be pushed up as-is during deployment.


## Development

- Make sure an up to date version of node is installed
- Pull down the repo and `cd` into it.  Run `npm install` to install node packages in package.json, including gulp and bower.  Node packages will save to a `node_modules` directory in the root of the repo.
- Install all front-end components and compile static assets by running `gulp default`.  During development, run `gulp watch` to detect static file changes automatically and run minification and compilation commands on the fly.
- Make sure up-to-date concatenated/minified files are pushed up to the repo when making changes to static files.


## Important files/folders:

### functions/base.php
Where functions and classes used throughout the theme are defined.

### functions/config.php
Where Config::$links, Config::$scripts, Config::$styles, and
Config::$metas should be defined.  Custom post types and custom taxonomies should
be set here via Config::$custom_post_types and Config::$custom_taxonomies.
Custom thumbnail sizes, menus, and sidebars should also be defined here.

### functions/theme.php
Theme-specific functions only should be defined here.  (Don't use functions.php
for this purpose--in this theme, it should only exist to import other files.)

### shortcodes.php
Where Wordpress shortcodes can be defined.  See example shortcodes for more
information.

### custom-post-types.php
Where the abstract custom post type and all its descendants live.

### static/
Where, aside from style.css in the root, all compiled/minified, theme-specific static
content such as javascript, images, and css should live.

### src/
Where static assets such as scss partials and unminified javascript should live.
With the exception of files in src/components/ (see below), the files in this directory
should be used to modify styles and logic on the frontend (do not modify minified
assets in static/.)

### src/components/
Where static assets installed by Bower should live.  Contents in this directory
should be ignored by the repo and are referenced only when running gulp tasks.


## Notes

This theme utilizes Twitter Bootstrap as its front-end framework.  Bootstrap
styles and javascript libraries can be utilized in theme templates and page/post
content.  For more information, visit http://twitter.github.com/bootstrap/

Note that this theme may not always be running the most up-to-date version of
Bootstrap.  For the most accurate documentation on the theme's current
Bootstrap version, visit http://bootstrapdocs.com/ and select the version number
found at the top of `components/bootstrap-sass-official/bower.json`.
