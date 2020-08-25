# VPM theme
A WordPress starter theme based on Timber library and Bootstrap 4. (Modified version of branch theme - forked from [Timber Starter Theme](http://github.com/Upstatement/timber-starter-theme))


## Features
---
- Based on Timber library.
- Uses Bootstrap 4 and is responsive
- NPM for package management.
- SASS
- Gulp for building
- Browsersync for multi-device testing

## Development Prerequisites
---

- [Composer](http://getcomposer.org)
- [Nodejs](http://nodejs.org)
- [SASS](http://sass-lang.com/install)
- [Gulp CLI](http://gulpjs.com/)
- [Browsersync](https://browsersync.io/)

## Configure for development
---
0. Make sure you have the Docker project running: `ddev start`
1. In the root of your WP website, run
   `composer install` to install Timber library.
2. Enable timber library in the admin area, or `ddev exec wp plugin activate timber-library`
2.1 Make sure you use Node 9.0:

```
  nvm use 9.0
```

3. Go to the theme's folder and run the install:

``` cd wp-content/themes/vpm
    composer install
    npm install
```
4. Enable VPM Branch theme in WP Admin, or `ddev exec wp theme activate vpm`
5. Run `gulp` to start theme complication and watching files.

## Documentations
---
1. Timber docs - https://timber.github.io/docs/
2. Twig docs - https://twig.symfony.com/doc/2.x/
3. Timber starter/sample repo - https://github.com/timber/starter-theme
4. Advanced Custom Fields docs - https://www.advancedcustomfields.com/resources/
