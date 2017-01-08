# Changelog

All Notable changes to `laravel-deezer-api` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased - YYYY-MM-DD
### Added
- Added a `count` property to the result object for easy access
- Added a `query` property to the result object so we can inspect what has been sent easily
- Ability to set the `order()` for the results

### Deprecated
### Fixed
### Removed
### Security

## 1.0.0 - 2017-01-07

### Added
- Can now set the search type with `type()` rather than passing it as an argument to `basicSearch()`
- Can now make an advanced search, see readme for more details

## 0.1.0 - 2017-01-07

### Added
- Ability to do a `basicSearch()` against the Deezer API
- Ability to set the results cache's duration
- Ability to turn on strict mode for Deezer
- Usage errors exceptions
- Unsupported search types exceptions

## Please ignore below this line!
## Unreleased - YYYY-MM-DD
### Added
### Deprecated
### Fixed
### Removed
### Security
