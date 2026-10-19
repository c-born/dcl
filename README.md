# GNUe DCL (C-Born fork)

This is a maintained fork of GNUe DCL (Double Choco Latte), a work order, ticket and time card tracker. The original project, [gnuedcl/dcl](https://github.com/gnuedcl/dcl), has had no commits since May 2020.

**Clone `master`. It is the current line of this fork.**

## What differs from upstream
- Runs on PHP 8.3 (upstream targets PHP 5 and 7), with Smarty 4.
- Tested against SQL Server 2022 using the `sqlsrv` PHP extension. The other database providers are still in the code but have not been tested.
- Added: an "Always Notify" setting (addresses that receive every work order and ticket notification), and Help > About shows the build, build date and PHP version.
- Fixed: date pickers now use the configured date format with a four digit year on every form.
- `php83-tests/` holds compatibility checks that need no database.

## Original notes
This repository is migrated from the Mercurial repository at SourceForge.

## License
GPL v2
