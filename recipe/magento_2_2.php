<?php

declare(strict_types=1);

/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

require_once 'recipe/common.php';
require_once __DIR__ . '/magento_2_1.php';
require_once __DIR__ . '/magento_2_2/artifact.php';
require_once __DIR__ . '/magento_2_2/maintenance.php';
require_once __DIR__ . '/magento_2_2/database.php';
require_once __DIR__ . '/magento_2_2/config.php';
require_once __DIR__ . '/magento_2_2/crontab.php';
require_once __DIR__ . '/magento_2_2/files.php';
require_once __DIR__ . '/magento_2_2/rollback.php';

desc('Build Artifact');
task('build', function () {
    set('deploy_path', '.');
    set('release_path', '.');
    set('current_path', '.');
    $origStaticOptions = get('static_deploy_options');
    set('static_deploy_options', '-f ' . $origStaticOptions);

    invoke('files:remove-generated');
    invoke('deploy:vendors');
    invoke('config:remove-dev-modules');
    invoke('files:generate');
    invoke('artifact:package');
})->local();

desc('Deploy artifact');
task('deploy-artifact', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'artifact:upload',
    'artifact:extract',
    'deploy:clear_paths',
    'deploy:shared',
    'deploy:writable',
    'maintenance:set:if-needed',
    'cache:clear:if-maintenance',
    'database:upgrade',
    'config:import',
    //'crontab:update',
    'deploy:override_shared',
    'deploy:symlink',
    'maintenance:unset',
    'cache:clear',
    'cache:enable',
    'deploy:unlock',
    'cleanup',
    'success',
]);
fail('deploy-artifact', 'deploy:failed');

# ---- Deployment Flow
desc('Deploy project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'config:remove-dev-modules',
    'deploy:clear_paths',
    'deploy:shared',
    'deploy:writable',
    'files:generate',
    'maintenance:set:if-needed',
    'cache:clear:if-maintenance',
    'database:upgrade',
    'config:import',
    //'crontab:update',
    'deploy:override_shared',
    'deploy:symlink',
    'maintenance:unset',
    'cache:clear',
    'cache:enable',
    'deploy:unlock',
    'cleanup',
    'success',
]);
