# CHANGELOG

This is the changelog of the **atelierspierrot/library** package.

You may find the original remote repository to <https://github.com/atelierspierrot/library.git>.
The `#xxx` marks of this changelog may reference a bug ticket you can find at 
<http://github.com/atelierspierrot/library/issues/XXX>. To see a full commit diff, 
go to <https://github.com/atelierspierrot/library/commit/COMMIT_HASH>.


* (upcoming release)

    * 01f0059 - usage of '@stable' dev-deps (piwi)

* v1.1.10 (2015-02-19 - 4492add)

    *   all Reporter objects are now in the `\Library\Reporter` namespace
    *   move the FileRotator in the `\Library\Tool` namespace
    *   rename the `\Library\Script` to `\Library\Tool\Encrypt` and delete internal aliases
    *   new ServiceContainer object
    * dc82e54 - fix #2: safely write an object in logger message's context (piwi)
    * 5a56cbb - Review of the Helper\Code::organizeArguments() method: (piwi)
    * 4e2d119 - review of the code helper (piwi)

* v1.1.9 (2015-02-14 - 8999d3c)

    *   the HTTP status codes of the `\Library\HttpFundamental\Response` object
        are now defined in the `\Patterns\Commons\HttpStatus` of the Patterns package
    * 0615580 - update of the patterns package required to version 1.0.9 or higher (piwi)
    * f3469ef - introducting the CHANGELOG of the library (piwi)
    * 8e3d3d1 - add a response sample in the demo (piwi)
    * 8f1efb9 - Update of the Response object: (piwi)
    * 8f43a10 - fix unit-tests failures (piwi)
    * 9b1c969 - new tests for helpers (piwi)
    * 111e7b4 - review of Helpers to pass tests :( (piwi)

* v1.1.8 (2015-01-30 - 102af69)

    * 3af9f40 - introducing TravisCI auto-unit-testing (piwi)
    * b320042 - create firsts unit-tests (piwi)
    * 1fa7440 - add PhpUnit to create unit-tests (piwi)
    * 3d5b7e7 - introducing library binaries (to test/execute library's classes) (piwi)
    * 22dcf1c - new RomanNumbe helper (piwi)
    * 560682d - add a 'wrap' method in TextHelper (piwi)
    * ae729e0 - PSR0 corrections (piwi)

* v1.1.7 (2015-01-08 - 0b967a0)

    * e886cb0 - avoid a Strict notice (piwi)

* v1.1.6 (2015-01-06 - dc8755f)

    * 0332d53 - review of the TextHelper (piwi)
    * 1085be5 - new license notice header for all scripts (piwi)

* v1.1.5 (2014-09-22 - c2076a2)

    * d8e0870 - adding a pagination tool (piwi)
    * 6fd694d - work on a File helper (piwi)
    * c9f3c7f - let table's footer customizable (piwi)
    * b0f15e3 - fix: double 'function' (piwi)
    * 00a6b4e - correction in the Number helper and new Morse code class (piwi)
    * 05e15c2 - fix a strict error on 'end()' and 'explode()' imbricated (piwi)
    * dce3d82 - fix: stop slugifying INI indexes (just stripping spaces) (Piero Wbmstr)

* v1.1.4 (2014-06-22 - 62cfb3d)

    * d42abd2 - new Array2INI converter (Piero Wbmstr)

* v1.1.3 (2014-06-14 - 78412f6)

    * ad6269e - new Number helper (Piero Wbmstr)

* v1.1.2 (2014-05-05 - adcc598)

    * 58d0ffd - Typo in the Code helper (Piero Wbmstr)
    * 7608b5c - Writing rules (Piero Wbmstr)
    * 23e8748 - Corrections of XML file type (Piero Wbmstr)

* v1.1.1 (2013-10-17 - b3d116c)

    * e219b42 - New Session classes (Piero Wbmstr)

* v1.1.0 (2013-10-13 - e47f595)

    * 656461c - Largest patterns package version (Piero Wbmstr)
    * c319ff0 - "getallheaders" must always return an array (Piero Wbmstr)
    * a806525 - New SemanticVersioning rules (Piero Wbmstr)

* v1.0.12 (2013-10-07 - d11a16f)

    * f41e6f7 - Creating branch for version 1.0 (Piero Wbmstr)
    * 8b3bebf - New XML content type handler (Piero Wbmstr)
    * 3c39d5c - Be compliant with the "RouterInterface->generateUrl()" signature (Piero Wbmstr)
    * 417c779 - Fixing the "getallheaders" miss for certain PHP version (Piero Wbmstr)
    * fc37630 - Modifications in the "fetchArguments" method of "Helper\Code" class (Piero Wbmstr)
    * 92fe8c9 - New Router base object (Piero Wbmstr)
    * acb69ec - Corrections in the HttpFundamental\Request class (Piero Wbmstr)
    * 7ce44dc - Simple error_reporting for demos (Piero Wbmstr)
    * 76000a2 - Corrections for PHP5.4 compliance + new demo models (Piero Wbmstr)
    * be3f748 - New settings for demos according to atelierspierrot/atelierspierrot/commons/MODEL.php (Piero Wbmstr)
    * 66daa9c - Corrections for PHP5.4 compliance (Piero Wbmstr)
    * 2b64a82 - Adding the possibility to get the current URL BEFORE any Apache rewriting (Piero Wbmstr)
    * f83df7f - Adding the LICENSE text (Piero Wbmstr)
    * dcf0fcf - New converter and populating the README (Piero Wbmstr)
    * 920cbe8 - New option in the Logger (Piero Wbmstr)
    * 466ed71 - New IE ContionalComment helper (Piero Wbmstr)
    * 4618810 - Corrections in the Directory Helper - upgrade to version 1.0.10 (Piero Wbmstr)
    * 4d153e5 - New Regex helper (Piero Wbmstr)
    * da4813c - New feature (Piero Wbmstr)
    * 8394e18 - Corrections in the Logger logic (Piero Wbmstr)
    * ba60410 - Update Text.php (Piero Wbmstr)
    * ae493c0 - New static configuration manager (Piero Wbmstr)
    * 74eea92 - New File and Directory features (Piero Wbmstr)
    * 058311a - New Code Helper method "implementsInterface" (Piero Wbmstr)
    * c89bad3 - The Psr\Log dependency is now for "dev" mode only (Piero Wbmstr)
    * cde5acb - Mini-correction on the DirectoryHelper (Piero Wbmstr)
    * 80747b6 - New Logger class (Piero Wbmstr)
    * 21b073a - Table Tool is ready (Piero Wbmstr)
    * 5745d2e - WIP - Table tool and Reporter (Piero Wbmstr)
    * ab71345 - Some new basic texts features (Piero Wbmstr)
    * 68a93f7 - New objects helpers (Piero Wbmstr)
    * 088e7a1 - Command line utilities (Piero Wbmstr)
    * 87e950e - New filesystem helper (Piero Wbmstr)
    * 72f1961 - New features of the URL helper (Piero Wbmstr)
    * 76f0667 - Modifications & new Helpers (Piero Wbmstr)
    * 5a2a1b4 - Corrections and new library helpers (Piero Wbmstr)
    * f229211 - Corrections and code comments (Piero Wbmstr)
    * f113d57 - Firs version of the Library (Piero Wbmstr)
    * 632ae3d - Initial commit (Piero Wbmstr)

