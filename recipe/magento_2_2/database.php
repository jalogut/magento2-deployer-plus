<?php
/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

use Deployer\Exception\RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;

const DB_UPDATE_NEEDED_EXIT_CODE = 2;

set('database_upgrade_needed', function () {
    try {
        run('{{release_path}}/{{magento_bin}} setup:db:status');
    } catch (ProcessFailedException $e) {
        if ($e->getProcess()->getExitCode() == DB_UPDATE_NEEDED_EXIT_CODE) {
            return true;
        }
        throw $e;
    } catch (RuntimeException $e) {
        if ($e->getExitCode() == DB_UPDATE_NEEDED_EXIT_CODE) {
            return true;
        }
        throw $e;
    }
    return false;
});

task('database:upgrade', function () {
    get('database_upgrade_needed') ?
        run('{{release_path}}/{{magento_bin}} setup:upgrade --keep-generated') :
        writeln('Skipped -> All Modules are up to date');
});