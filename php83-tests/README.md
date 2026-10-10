# PHP 8.3 compatibility tests for DCL (no database needed)

Run from anywhere with PHP 8.3 CLI. `DCL=/path/to/dcl/dcl` is the application folder.

1. **Syntax lint** of every file:
   `find $DCL -name '*.php' -not -path '*/templates_c/*' -print0 | xargs -0 -n1 php8.3 -l | grep -v '^No syntax errors'`
2. **Compile all templates** with the vendored Smarty (catches template syntax and plugin problems; registers placeholder
   objects `Org`, `Contact`, `ViewData` that DCL registers at runtime):
   `php8.3 php83-tests/compile-all.php $DCL/vendor/Smarty $DCL`
3. **Load every class** (catches fatal errors such as incompatible method signatures, which are warnings in PHP 7 but fatal in PHP 8):
   `ls $DCL/inc/{lib,helpers,models,controllers,presenters,validators,exceptions,services}/*.php | sed -E 's#.*/##; s#\.php$##' | sort -u | xargs -P6 -I{} php8.3 php83-tests/class-load.php $DCL {}`
   (`DateTimeHelper` and `ContactNoteController` define classes under other names; they are included explicitly by the app.)
4. **PHPCompatibility scan** (PHP_CodeSniffer 3 with PHPCompatibility 9.3.5, run on a PHP with the XML extension):
   `php phpcs.phar --standard=PHPCompatibility --runtime-set testVersion 8.0- --extensions=php --ignore='dcl/vendor/*,dcl/templates/templates_c/*,dcl/files/*' dcl`

What these tests cannot cover: database access (needs the `sqlsrv`/`pdo_sqlsrv` PHP extensions for 8.3 and a copy of the database),
runtime warnings/deprecations (dynamic properties in PHP 8.2, null passed to non-nullable parameters in PHP 8.1), and real page rendering.
