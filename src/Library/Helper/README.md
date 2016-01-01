\Library\Helper
===============

The `Library\Helper` namespace defines some classes commonly used following these rules:

-   all methods are static,
-   methods MUST NOT send error while calling them without the right arguments or with no
    argument at all: they MUST return an expected "wrong" value (`false` for a validation,
    an empty string for a constructor etc.).

More, a helper class SHOULD always have a [UnitTest](http://phpunit.de/manual/) test in
`tests/testLibrary/Helper/`.
 