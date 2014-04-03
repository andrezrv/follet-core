Follet Core
===========

A simple and lightweight but powerful framework based on [_s](http://underscores.me/) and meant to create rock solid themes using WordPress best practices.

Please note that this is not a theme, and it won't work if you just drop it into your `themes` folder. This is a library that you should include into your theme to take advantage of its functionality.

## Getting Started

1. `git clone git@github.com:andrezrv/follet-core.git` into `/wp-content/themes/my-theme/includes/`, or download and unzip `follet-core.zip` and copy the `follet-core` folder to your `/wp-content/themes/my-theme/includes/` directory.
2. Add the following code, customized to your own needs, to your `functions.php` file:

```PHP
// Top of file ...
require get_template_directory() . '/includes/follet-core/follet-load.php';

// Your own code here ...

// End of file ...
do_action( 'follet_setup' ); // --> This will initialize the Follet Core
```

## What did that do?

Just by initializing Follet core, the following features are automatically available:

* Autoloaded textdomain for text localization.
* Autoloaded theme support for automatic feed links, featured images, post formats, custom background, HTML5 and Infinite Scroll for Jetpack.
* Autoloaded `style.css` and [Bootstrap](http://getbootstrap.com) styles and scripts.
* Autoloaded editor styles.
* Filters for Bootstrap markup support on built-in template tags.
* A number of reusable functions and template tags.
* An API that lets you manage your theme internal options without having to deal with the sometimes complicated idiosincracies of built-in options and theme mods.

## Contributing

If you feel like you want to help this project by adding something you think useful, you can make your pull request against the master branch :)

More docs are coming soon!