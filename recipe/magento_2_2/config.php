<?php
/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

use Deployer\Exception\RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;

const OUTPUT_CONFIG_IMPORT_NEEDED = 'This command is unavailable right now. ' .
    'To continue working with it please run app:config:import or setup:upgrade command before.';

set('config_import_needed', function () {
    try {
        // NOTE: Workaround until "app:config:status" is available on Magento 2.2.3
        run('{{bin/php}} {{release_path}}/{{magento_bin}} config:set workaround/check/config_status 1');
    } catch (ProcessFailedException $e) {
        if (trim($e->getProcess()->getOutput()) == OUTPUT_CONFIG_IMPORT_NEEDED) {
            return true;
        }
    } catch (RuntimeException $e) {
        if (trim($e->getOutput()) == OUTPUT_CONFIG_IMPORT_NEEDED) {
            return true;
        }
    }
    return false;
});

task('config:import', function () {
    get('config_import_needed') ?
        run('{{bin/php}} {{release_path}}/{{magento_bin}} app:config:import') :
        writeln('Skipped -> App config is up to date');
});