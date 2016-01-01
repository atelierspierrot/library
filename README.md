PHP Library
===========

[![Build Status](http://travis-ci.org/atelierspierrot/library.svg?branch=master)](http://travis-ci.org/atelierspierrot/library)
[![Code Climate](http://codeclimate.com/github/atelierspierrot/library/badges/gpa.svg)](http://codeclimate.com/github/atelierspierrot/library)
[![documentation](http://img.ateliers-pierrot-static.fr/read-the-doc.svg)](http://docs.ateliers-pierrot.fr/library/)

The PHP library package of Les Ateliers Pierrot


Presentation
------------

This package is a set of PHP basic classes commonly used (in our work) to facilitate
other developments. It contains some global classes to extend to start on a robuste base,
some useful helpers for some methods often used etc. For a full review of what the library
embeds, have a look at the `src/Library/` directory contents.

This package is based on our [PHP Patterns package](http://github.com/atelierspierrot/patterns).


Installation
------------

For a complete information about how to install this package and load its namespace, 
please have a look at [our *USAGE* documentation](http://github.com/atelierspierrot/atelierspierrot/blob/master/USAGE.md).

If you are a [Composer](http://getcomposer.org/) user, just add the package to the 
requirements of your project's `composer.json` manifest file:

```json
"atelierspierrot/library": "dev-master"
```

You can use a specific release or the latest release of a major version using the appropriate
[version constraint](http://getcomposer.org/doc/01-basic-usage.md#package-versions).

Note that the library depends on the external package [PHP Patterns](https://github.com/atelierspierrot/patterns).


Quick overview
--------------

### HTTP Fundamental

The `Library\HttpFundamental` namespace defines a set of classes to handle a classic HTTP
request/response protocol.

### Helpers

The `Library\Helper` namespace defines some classes commonly used following these rules:

- all methods are static,
- methods MUST NOT send error while calling them without the right arguments or with no
  argument at all.


Author & License
----------------

>    PHP Library

>    http://github.com/atelierspierrot/library

>    Copyleft (ↄ) 2013-2016 Pierre Cassat and contributors

>    Licensed under the GPL Version 3 license.

>    http://opensource.org/licenses/GPL-3.0

>    ----

>    Les Ateliers Pierrot - Paris, France

>    <http://www.ateliers-pierrot.fr/> - <contact@ateliers-pierrot.fr>
