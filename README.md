PHP Library
===========

The PHP library package of Les Ateliers Pierrot


## Presentation

This package is a set of PHP basic classes commonly used (in our work) to facilitate
other developments. It contains some global classes to extend to begin on a robuste base,
some useful helpers for some methods often used etc. For a full review of what the library
embeds, have a look at the `src/` directory contents.


## Usage

As for all our work, we try to follow the coding standards and naming rules most commonly in use:

-   the [PEAR coding standards](http://pear.php.net/manual/en/standards.php)
-   the [PHP Framework Interoperability Group standards](https://github.com/php-fig/fig-standards).

Knowing that, all classes are named and organized in an architecture to allow the use of the
[standard SplClassLoader](https://gist.github.com/jwage/221634).

The whole package is embedded in the `Library` namespace.


## Installation

You can use this package in your work in many ways. Note that it depends on the external
package [PHP Patterns](https://github.com/atelierspierrot/patterns).

First, you can clone the [GitHub](https://github.com/atelierspierrot/library) repository
and include it "as is" in your poject:

    https://github.com/atelierspierrot/patterns
    https://github.com/atelierspierrot/library

You can also download an [archive](https://github.com/atelierspierrot/library/downloads)
from Github.

Then, to use the package classes, you just need to register the `Library` AND the `Patterns`
namespace directory using the [SplClassLoader](https://gist.github.com/jwage/221634) or
any other custom autoloader (if required, a copy of the `SplClassLoader` is proposed in
the package):

    require_once '.../src/SplClassLoader.php';
    $patternsLoader = new SplClassLoader('Patterns', '/path/to/patterns/package/src');
    $patternsLoader->register();
    $libraryLoader = new SplClassLoader('Library', '/path/to/package/src');
    $libraryLoader->register();

If you are a [Composer](http://getcomposer.org/) user, just add the package to your requirements
in your `composer.json`:

    "require": {
        ...
        "atelierspierrot/library": "dev-master"
    }

The namespaces will be automatically added to the project Composer autoloader.


## Quick overview

### Helpers

The `Library\Helper` namespace defines some classes commonly used following these rules:

- all methods are static,
- methods may not send error while calling them without the right arguments or with no
  argument at all.


## Development

To install all PHP packages for development, just run:

    ~$ composer install --dev

A documentation can be generated with [Sami](https://github.com/fabpot/Sami) running:

    ~$ php vendor/sami/sami/sami.php render sami.config.php

The latest version of this documentation is available online at <http://docs.ateliers-pierrot.fr/library/>.


## Author & License

>    Patterns

>    https://github.com/atelierspierrot/patterns

>    Copyleft 2013, Pierre Cassat and contributors

>    Licensed under the GPL Version 3 license.

>    http://opensource.org/licenses/GPL-3.0

>    ----

>    Les Ateliers Pierrot - Paris, France

>    <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
