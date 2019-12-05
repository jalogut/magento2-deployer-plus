<?php

declare(strict_types=1);

/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

set('languages', 'en_US');
set('static_deploy_options', '--exclude-theme=Magento/blank');

task('files:compile', '{{bin/php}} {{magento_bin}} setup:di:compile');
task('files:static_assets', '{{bin/php}} {{magento_bin}} setup:static-content:deploy {{languages}} {{static_deploy_options}}');
task(
    'files:permissions',
    'cd {{magento_dir}} && chmod -R g+w var vendor pub/static pub/media app/etc && chmod u+x bin/magento'
);

desc('Generate Magento Files');
task('files:generate', [
    'files:compile',
    'files:static_assets',
    'files:permissions',
]);
