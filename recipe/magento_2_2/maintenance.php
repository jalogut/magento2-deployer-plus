<?php
/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

task('maintenance:set:if-needed', function () {
    get('database_upgrade_needed') || get('config_import_needed') ?
        invoke('maintenance:set') :
        writeln('Skipped -> Maintenance is not needed');
});
