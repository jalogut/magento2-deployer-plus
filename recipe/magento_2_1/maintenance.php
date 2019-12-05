<?php

declare(strict_types=1);

/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

task('maintenance:set', function () {
    # IMPORTANT: do not use {{current_path}} for the "-f" check.
    # {{current_path}} returns error if symlink does not exists
    test('[ -f {{deploy_path}}/current/{{magento_bin}} ]') ?
        run('{{bin/php}} {{current_path}}/{{magento_bin}} maintenance:enable') :
        writeln('Skipped -> current not found');
});

task('maintenance:unset', function () {
    # IMPORTANT: do not use {{current_path}} for the "-f" check.
    # {{current_path}} returns error if symlink does not exists
    if (!test('[ -f {{deploy_path}}/current/{{magento_bin}} ]')) {
        writeln('Skipped -> current not found');
        return;
    }
    test('[ -f {{deploy_path}}/current/{{magento_dir}}/var/.maintenance.flag ]') ?
        run('{{bin/php}} {{current_path}}/{{magento_bin}} maintenance:disable') :
        writeln('Skipped -> maintenance is already unset');
});
