# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Version](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added

### Changed

### Removed

## [2.7.1] - 16-04-2020
### Changed
* fix task `files:permissions` to change to `magento_dir` folder before set permissions

## [2.7.0] - 05-12-2019
### Added
* task `cache:enable` to enable modules during deployment

### Changed
* Fixes wrong permissions after extracting archive

## [2.6.0] - 05-12-2019
### Changed
* fix Grumphp Tests

## [2.5.0] - 13-03-2019
### Added
* task `files:remove-generated` to delete generated folder content

## [2.4.0] - 25-02-2019
### Changed
* `database:upgrade` to split `schema:upgrade` and `data:upgrade`

## [2.3.0] - 18-12-2018
### Changed

* `files:permissions` task now runs faster and is more concise.

## [2.2.2] - 19-10-2018
### Changed

* Fix `config:remove-dev-modules` step was executed local instead of remote

## [2.2.1] - 05-09-2018
### Added

* Exclude test stub modules during artifact generation. Magento creates automatically these modules during integration
tests. If these modules happen to end up in your production server, the checkout gets broken.

    * https://github.com/magento/magento2/issues/12696
    * https://github.com/magento/magento2/issues/12679

## [2.2] - 24-07-2018
### Added
* Task `config:remove-dev-modules` that removes modules specified in `dev_modules` from `app/etc/config.php` during deployment.
Modules installed with composer `require-dev` that are present in `app/etc/config.php` must be added here to prevent problems with `bin/magento setup:db:status`

## [2.1] - 05-07-2018
### Added
* Task `deploy:override_shared` to replace dirs and create symlinks taking the dir from new release as source
    * This is needed for caches. They cannot be symlinked during the files generation but they need to replace previous
    ones and be shared among servers in multi-server setups.
* New params `override_shared_dirs` and `override_shared_files`

### Changed
* Cache dirs are moved from `shared_dirs` to `override_shared_dirs`

### Removed
* Default values for `writable_dirs` and `clean_paths` have been removed. They are no longer needed as the dir
permissions are set with `files:permissions` tasks and the `static/_cache` is overwritten with `override_shared_dirs`
* Task `crontab:update` removed because it appends new cronjobs with every release. We'll fix it in a future release

## [2.0.1] - 03-07-2018
### Changed
* Update require recipe path on sample files. Now the whole relative path to vendor dir is needed.

## [2.0] - 29-06-2018
### Added
* `var` subdirectories added to `shared_dirs`.
    * `/var/log`
    * `/var/backups`
    * `/var/cache`
    * `/var/page_cache`
    * `/var/session`
* `maintenance:unset` during rollback
* Use `app:config:status` command on Magento versions `>=2.2.5`

### Changed
* Use `deployer/dist` instead of `deployer/deployer` to avoid conflicts with composer dependencies.

### Removed
* remove `var` dir from `shared_dirs` because `generated` and `view_preprocessed` should not be shared among releases. If not, we are changing the current generated code while building the new release.

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
