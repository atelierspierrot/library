PHP Library
===========

[![Build Status](https://travis-ci.org/atelierspierrot/library.svg?branch=master)](https://travis-ci.org/atelierspierrot/library)
[![documentation](http://img.ateliers-pierrot-static.fr/readthe-doc.png)](http://docs.ateliers-pierrot.fr/library/)
The PHP library package of Les Ateliers Pierrot


## Presentation

This package is a set of PHP basic classes commonly used (in our work) to facilitate
other developments. It contains some global classes to extend to start on a robuste base,
some useful helpers for some methods often used etc. For a full review of what the library
embeds, have a look at the `src/Library/` directory contents.

This package is based on our [PHP Patterns package](http://github.com/atelierspierrot/patterns).


## Usage

### First notes about standards

As for all our work, we try to follow the coding standards and naming rules most commonly in use:

-   the [PEAR coding standards](http://pear.php.net/manual/en/standards.php)
-   the [PHP Framework Interoperability Group standards](https://github.com/php-fig/fig-standards).

Knowing that, all classes are named and organized in an architecture to allow the use of the
[standard SplClassLoader](https://gist.github.com/jwage/221634).

The whole package is embedded in the `Library` namespace.

In this README documentation, the key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
"SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://www.ietf.org/rfc/rfc2119.txt).

### Installation

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

### HTTP Fundamental

The `Library\HttpFundamental` namespace defines a set of classes to handle a classic HTTP
request/response protocol.

### Helpers

The `Library\Helper` namespace defines some classes commonly used following these rules:

- all methods are static,
- methods MUST NOT send error while calling them without the right arguments or with no
  argument at all.


## Development

To install all PHP packages for development, just run:

    ~$ composer install --dev

A documentation can be generated with [Sami](https://github.com/fabpot/Sami) running:

    ~$ php vendor/sami/sami/sami.php render sami.config.php

The latest version of this documentation is available online at <http://docs.ateliers-pierrot.fr/library/>.


## Author & License

>    PHP Library

>    http://github.com/atelierspierrot/library

>    Copyleft (ↄ) 2013-2016 Pierre Cassat and contributors

>    Licensed under the GPL Version 3 license.

>    http://opensource.org/licenses/GPL-3.0

>    ----

>    Les Ateliers Pierrot - Paris, France

>    <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
