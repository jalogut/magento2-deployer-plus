<?php

declare(strict_types=1);

/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

task('crontab:update', function () {
    if (test('[ -L {{deploy_path}}/current ]')) {
        run("{{bin/php}} {{current_path}}/{{magento_bin}} cron:remove");
    }
    run("{{bin/php}} {{release_path}}/{{magento_bin}} cron:install");
});
