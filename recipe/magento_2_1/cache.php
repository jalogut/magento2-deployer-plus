<?php

declare(strict_types=1);

/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

task('cache:clear:magento', '{{bin/php}} {{magento_bin}} cache:flush');

task('cache:clear', function () {
    invoke('cache:clear:magento');
});

task('cache:clear:if-maintenance', function () {
    test('[ -f {{deploy_path}}/current/{{magento_dir}}/var/.maintenance.flag ]') ?
        invoke('cache:clear:magento') :
        writeln('Skipped -> maintenance is not set');
});
