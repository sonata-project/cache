# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [2.2.0](https://github.com/sonata-project/cache/compare/2.1.1...2.2.0) - 2021-08-03
### Added
- [[#209](https://github.com/sonata-project/cache/pull/209)] Added support for `psr/log` 2 and 3 ([@jordisala1991](https://github.com/jordisala1991))

### Removed
- [[#209](https://github.com/sonata-project/cache/pull/209)] Removed support for PHP < 7.3 ([@jordisala1991](https://github.com/jordisala1991))

## [2.1.1](https://github.com/sonata-project/cache/compare/2.1.0...2.1.1) - 2021-02-15
### Fixed
- [[#174](https://github.com/sonata-project/cache/pull/174)] Php version constraint ([@greg0ire](https://github.com/greg0ire))

## [2.1.0](https://github.com/sonata-project/cache/compare/2.0.1...2.1.0) - 2021-01-05
### Added
- [[#165](https://github.com/sonata-project/cache/pull/165)] Allow PHP8 ([@VincentLanglet](https://github.com/VincentLanglet))

### Fixed
- [[#117](https://github.com/sonata-project/cache/pull/117)] Fixed the `PredisCluster` namespace in `PRedisCacheAdapter` ([@nanofelis](https://github.com/nanofelis))

## [2.0.1](https://github.com/sonata-project/cache/compare/2.0.0...2.0.1) - 2017-12-08
### Fixed
- fatal error when using the memcached adapter

## [2.0.0](https://github.com/sonata-project/cache/compare/1.x...2.0.0) - 2016-08-29
### Added
- Type hinting on most methods

## [1.1.0](https://github.com/sonata-project/cache/compare/1.0.7...1.1.0) - 2016-08-29
### Added
- Added php as a required dependency
- Added PSR logger as a required dependency

### Changed
- The directory structure is more standard

### Removed
- internal test classes are now excluded from the autoloader
