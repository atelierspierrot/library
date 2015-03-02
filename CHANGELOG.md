# CHANGELOG

This is the changelog of the **atelierspierrot/library** package.

You may find the original remote repository to <https://github.com/atelierspierrot/library.git>.
The `#xxx` marks of this changelog may reference a bug ticket you can find at 
<http://github.com/atelierspierrot/library/issues/XXX>. To see a full commit diff, 
go to <https://github.com/atelierspierrot/library/commit/COMMIT_HASH>.


* v2.0.0 (next release)

    * b85fd33 - update of the documentation (piwi <me@e-piwi.fr>)
    * 5e06fb4 - the library is now under the Apache 2.0 license (piwi <me@e-piwi.fr>)
    * 72682be - no more mention of the HttpFundamental in the README (piwi <me@e-piwi.fr>)
    * afe7547 - remove the Library\Event namespace (piwi <me@e-piwi.fr>)
    * 3206648 - On feature-event-manager: event manager wip (piwi <me@e-piwi.fr>)
    * 3d26f5f - index on feature-event-manager: 5cdfc25 review of the CHANGELOG (piwi <me@e-piwi.fr>)
    * b7d89f6 - the documentation must be closed by default (piwi <me@e-piwi.fr>)
    * b7fbb9d - remove the Library\ServiceContainer namespace (piwi <me@e-piwi.fr>)
    * d8c1674 - remove the Library\HttpFundamental namespace (piwi <me@e-piwi.fr>)
    * b187aea - review of the service-container (piwi <me@e-piwi.fr>)
    * 8ad0b95 - prepare an upgrae shell script (piwi <me@e-piwi.fr>)
    * d21b9fd - replace 'htmlentities' by 'htmlspecialchars' when cleaning request arguments (piwi <me@e-piwi.fr>)
    * 3679766 - clean arguments on getting (piwi <me@e-piwi.fr>)
    * 53b74f6 - simple comments and code review of the Request object (piwi <me@e-piwi.fr>)
    * bc6c704 - review of the service-container (piwi <me@e-piwi.fr>)
    * 16cfc45 - add test demonstation files from 'wip' (piwi <me@e-piwi.fr>)
    * e77d85c - Merge branch 'wip' into dev (piwi <me@e-piwi.fr>)
    * 3caf37b - Full review of the demo: (piwi <me@e-piwi.fr>)
    * 7fd5469 - include the original AbstractResponse in the class (piwi <me@e-piwi.fr>)
    * 6b619d8 - update demo with last classes renaming (piwi <me@e-piwi.fr>)
    * d567849 - update the SplClassLoader with my own fork (piwi <me@e-piwi.fr>)
    * 48c4427 - add info in changelog (piwi <me@e-piwi.fr>)
    * 4324c6d - update the demo with last updates (piwi <me@e-piwi.fr>)
    * 76d77ad - update min PHP version to 5.4 for traits usage (piwi <me@e-piwi.fr>)
    * 2ce5652 - review of the StaticConfiguration/Config object (piwi <me@e-piwi.fr>)
    * 77c926f - update the Logger to use the new file rotator (piwi <me@e-piwi.fr>)
    * a553e00 - new Service Container object (piwi <me@e-piwi.fr>)
    * b43839e - move the Library/FileRotator to Library/Tool/FileRotator (piwi <me@e-piwi.fr>)
    * 7507fc5 - move the Library/Crypt to Library/Tool/Encrypt (piwi <me@e-piwi.fr>)
    * 687baaf - move all Reporter objects in \Library\Reporter (piwi <me@e-piwi.fr>)    * dc82e54 - fix #2: safely write an object in logger message's context (piwi <me@e-piwi.fr>)
    * 5a56cbb - Review of the Helper\Code::organizeArguments() method: (piwi <me@e-piwi.fr>)
    * 4e2d119 - review of the code helper (piwi <me@e-piwi.fr>)

* v1.1.9 (2015-02-14 - 8999d3c)

    - the HTTP status codes of the `\Library\HttpFundamental\Response` object
    are now defined in the `\Patterns\Commons\HttpStatus` of the Patterns package

    * 0ab5463 - new documentation link in the README (piwi <me@e-piwi.fr>)
    * 0615580 - update of the patterns package required to version 1.0.9 or higher (piwi <me@e-piwi.fr>)
    * f3469ef - introducting the CHANGELOG of the library (piwi <me@e-piwi.fr>)
    * 8e3d3d1 - add a response sample in the demo (piwi <me@e-piwi.fr>)
    * 8f1efb9 - Update of the Response object: (piwi <me@e-piwi.fr>)
    * 8f43a10 - fix unit-tests failures (piwi <me@e-piwi.fr>)
    * 9b1c969 - new tests for helpers (piwi <me@e-piwi.fr>)
    * ee009a8 - temporary directory for tests (piwi <me@e-piwi.fr>)
    * bbde6ba - fix a wrong method name in the demo (piwi <me@e-piwi.fr>)
    * 421ef37 - add a missing blank line (piwi <me@e-piwi.fr>)
    * 111e7b4 - review of Helpers to pass tests :( (piwi <me@e-piwi.fr>)
    * 0358630 - new organization of tests (piwi <me@e-piwi.fr>)
    * 5addfee - TravisCI badge in the README (piwi <me@e-piwi.fr>)

* v1.1.8 (2015-01-30 - 102af69)

    * cc108f0 - no test binary in master (piwi <me@e-piwi.fr>)
    * 3af9f40 - introducing TravisCI auto-unit-testing (piwi <me@e-piwi.fr>)
    * 2088b95 - fix a wrong temporary directory path (piwi <me@e-piwi.fr>)
    * b320042 - create firsts unit-tests (piwi <me@e-piwi.fr>)
    * 1fa7440 - add PhpUnit to create unit-tests (piwi <me@e-piwi.fr>)
    * 3d5b7e7 - introducing library binaries (to test/execute library's classes) (piwi <me@e-piwi.fr>)
    * 22dcf1c - new RomanNumbe helper (piwi <me@e-piwi.fr>)
    * 560682d - add a 'wrap' method in TextHelper (piwi <me@e-piwi.fr>)
    * ae729e0 - PSR0 corrections (piwi <me@e-piwi.fr>)

* v1.1.7 (2015-01-08 - 0b967a0)

    * e886cb0 - avoid a Strict notice (piwi <me@e-piwi.fr>)

* v1.1.6 (2015-01-06 - dc8755f)

    * 0332d53 - review of the TextHelper (piwi <me@e-piwi.fr>)
    * 1085be5 - new license notice header for all scripts (piwi <me@e-piwi.fr>)
    * e04fed9 - update of the gitignore (piwi <me@e-piwi.fr>)

* v1.1.5 (2014-09-22 - c2076a2)

    * d8e0870 - adding a pagination tool (piwi <me@e-piwi.fr>)
    * 6fd694d - work on a File helper (piwi <me@e-piwi.fr>)
    * c9f3c7f - let table's footer customizable (piwi <me@e-piwi.fr>)
    * b0f15e3 - fix: double 'function' (piwi <me@e-piwi.fr>)
    * 00a6b4e - correction in the Number helper and new Morse code class (piwi <me@e-piwi.fr>)
    * 05e15c2 - fix a strict error on 'end()' and 'explode()' imbricated (piwi <me@e-piwi.fr>)
    * dce3d82 - fix: stop slugifying INI indexes (just stripping spaces) (Piero Wbmstr <me@e-piwi.fr>)

* v1.1.4 (2014-06-22 - 62cfb3d)

    * bd505ba - cleanup & review of sources (Piero Wbmstr <me@e-piwi.fr>)
    * d42abd2 - new Array2INI converter (Piero Wbmstr <me@e-piwi.fr>)

* v1.1.3 (2014-06-14 - 78412f6)

    * 48a0d66 - new piwi github username (Piero Wbmstr <me@e-piwi.fr>)
    * ad6269e - new Number helper (Piero Wbmstr <me@e-piwi.fr>)

* v1.1.2 (2014-05-05 - adcc598)

    * 28c5c9d - No test file on master (Piero Wbmstr <me@e-piwi.fr>)
    * 58d0ffd - Typo in the Code helper (Piero Wbmstr <me@e-piwi.fr>)
    * 7608b5c - Writing rules (Piero Wbmstr <me@e-piwi.fr>)
    * 23e8748 - Corrections of XML file type (Piero Wbmstr <me@e-piwi.fr>)
    * 154cd68 - Renaming "PieroWbmstr" in lowercase (Piero Wbmstr <me@e-piwi.fr>)

* v1.1.1 (2013-10-17 - b3d116c)

    * e219b42 - New Session classes (Piero Wbmstr <me@e-piwi.fr>)

* v1.1.0 (2013-10-13 - e47f595)

    * 656461c - Largest patterns package version (Piero Wbmstr <me@e-piwi.fr>)
    * 24a2bd6 - No test file in "master" (Piero Wbmstr <me@e-piwi.fr>)
    * c319ff0 - "getallheaders" must always return an array (Piero Wbmstr <me@e-piwi.fr>)
    * a806525 - New SemanticVersioning rules (Piero Wbmstr <me@e-piwi.fr>)
