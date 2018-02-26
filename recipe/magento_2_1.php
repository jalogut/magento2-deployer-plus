<?php
/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

require 'recipe/common.php';
require __DIR__ . '/magento_2_1/files.php';
require __DIR__ . '/magento_2_1/maintenance.php';
require __DIR__ . '/magento_2_1/database.php';
require __DIR__ . '/magento_2_1/cache.php';
require __DIR__ . '/magento_2_1/rollback.php';

# ----- Deployment properties ---
set('ci_branch', 'develop');
set('default_timeout', 900);
// [Optional] git repository
set('repository', '');
// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

# ----- Magento properties -------
set('magento_dir', 'magento');
set('magento_bin', '{{magento_dir}}/bin/magento');

set('shared_files', [
    '{{magento_dir}}/app/etc/env.php',
]);
set('shared_dirs', [
    '{{magento_dir}}/var',
    '{{magento_dir}}/pub/media',
]);
set('writable_dirs', [
    '{{magento_dir}}/var',
    '{{magento_dir}}/pub/static',
    '{{magento_dir}}/pub/media',
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
    'maintenance:set',
    'database:upgrade',
    'deploy:symlink',
    'clear:cache',
    'deploy:unlock',
    'cleanup',
    'success',
]);

after('deploy:failed', 'deploy:unlock');

before('rollback', 'rollback:validate');
after('rollback', 'maintenance:unset');
after('rollback', 'clear:cache');