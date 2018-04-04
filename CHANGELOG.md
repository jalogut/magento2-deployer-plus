# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Version](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added

### Changed

### Removed

## [1.1.1] - 04-04-2018
### Added
* Execute `setup:upgrade` and `app:config:import` in no interaction mode

## [1.1] - 04-04-2018
### Removed
* Remove `writable_dirs` param because permissions are already set on `files:permissions` task
* Remove `ci_branch` param as it is not used

## [1.0.7] - 04-04-2018
### Removed
* Sample configuration of `maintenance` and `cache` tasks only executed for master server. These tasks should be executed on all servers unless the `var` folder is shared among server instances

## [1.0.6] - 02-04-2018
### Changed
* Fix `cache:clear` command call on `rollback` task

## [1.0.5] - 01-04-2018
### Added
* Add `cache:clear:if-maintenance` to flush all caches after maintenance is set
* Add timestamp for `release_name` in sample files

## [1.0.4] - 27-03-2018
### Added
* `maintenance:unset` after switching symlink

### Removed
* Remove not needed `maintenance:unset` during rollback

## [1.0.3] - 27-03-2018
### Changed
* Fix `crontab:update` execute only if current path exists
* Fix `cache:clear` properly call task using `invoke()`
* Task destroy artefact after extraction

## [1.0.2] - 21-03-2018
### Changed
* Fix exclude `var/cache` and `var/page_cache` from artifact. Excluding `cache` was also excluding some module cache folders that are needed.

## [1.0.1] - 20-03-2018
### Changed
* Fix `crontab:update` step due to changes introduced on version 2.2.2
    * [https://github.com/magento/magento2/commit/79e9054aa2fd66a6c804774617cd0f5d14ffc49a](https://github.com/magento/magento2/commit/79e9054aa2fd66a6c804774617cd0f5d14ffc49a)

## [1.0] - 05-03-2018
### Added
* Use deployers `{{bin/php}}` with every execution of `{{magento_bin}}`

## [0.2] - 26-01-2018
### Added
* Task `cache:clear:magento`

### Changed
* Add `var` dir in `shared_dirs`. It makes more sense for multi-servers setup to have `var` dir shared among servers.
* Change task name `clear:cache` to `cache:clear`

### Removed
* Remove `var/.ip.maintenance` and `var/backups from shared configuration

## [0.1] - 25-01-2018
* First working version
