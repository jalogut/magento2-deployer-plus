<?php
/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

require 'recipe/common.php';
require __DIR__ . '/magento_2_1.php';
require __DIR__ . '/magento_2_2/artifact.php';
require __DIR__ . '/magento_2_2/maintenance.php';
require __DIR__ . '/magento_2_2/database.php';
require __DIR__ . '/magento_2_2/config.php';
require __DIR__ . '/magento_2_2/crontab.php';
require __DIR__ . '/magento_2_2/rollback.php';

desc('Build Artifact');
task('build', function () {
    set('deploy_path', '.');
    set('current_path', '.');
    $origStaticOptions = get('static_deploy_options');
    set('static_deploy_options', '-f ' . $origStaticOptions);

    invoke('deploy:vendors');
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
    'database:upgrade',
    'config:import',
    'crontab:update',
    'deploy:symlink',
    'maintenance:unset',
    'cache:clear',
    'deploy:unlock',
    'cleanup',
    'success',
]);

# ---- Deployment Flow
desc('Deploy project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:shared',
    'deploy:writable',
    'files:generate',
    'maintenance:set:if-needed',
    'database:upgrade',
    'config:import',
    'crontab:update',
    'deploy:symlink',
    'maintenance:unset',
    'cache:clear',
    'deploy:unlock',
    'cleanup',
    'success',
]);
