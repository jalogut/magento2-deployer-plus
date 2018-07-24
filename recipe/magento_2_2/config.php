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

/*
 * In dev_modules, Magento modules can be specified that should be removed from app/etc/config.php during deployment
 * Modules installed with "require-dev" that are present in app/etc/config.php must be added here to prevent problems
 * with bin/magento setup:db:status
 */
set('dev_modules', []);

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
        run('{{bin/php}} {{release_path}}/{{magento_bin}} app:config:import --no-interaction') :
        writeln('Skipped -> App config is up to date');
});

task('config:remove-dev-modules', function () {
    $modules = get('dev_modules');
    if (!empty($modules)) {
        $configFile = get('magento_dir') . '/app/etc/config.php';
        $config = include $configFile;
        foreach ($modules as $module) {
            if (isset($config['modules'][$module])) {
                unset($config['modules'][$module]);
                writeln('Removed: ' . $module);
            } else {
                writeln('Not installed: ' . $module);
            }
        }
        \file_put_contents($configFile, "<?php\nreturn " . \var_export($config, true) . ';');
    }
});
