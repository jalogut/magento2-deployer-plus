# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Version](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added

### Changed

### Removed

## [0.2] - 26-01-2018
### Added
* Task `cache:clear:magento`

### Changed
* Add `var` dir in `shared_dirs`. It makes more sense for multi-servers setup to have `var` dir shared among servers.
* Change task name `clear:cache` to `cache:clear`

### Removed
* Remove `var/.ip.maintenance` and `var/backups from shared configuration

## [0.1] - 25-01-2018
First working version
