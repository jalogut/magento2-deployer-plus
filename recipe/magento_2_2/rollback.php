<?php
/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

use Deployer\Exception\Exception;

task('rollback:validate', function () {
    $releases = get('releases_list');

    if (!isset($releases[1])) {
        writeln("<comment>No more releases you can revert to.</comment>");
        return;
    }

    set('release_path', "{{deploy_path}}/releases/{$releases[1]}");
    if (get('database_upgrade_needed') || get('config_import_needed')) {
        $errorMessage = 'Secure rollback not possible' . PHP_EOL . PHP_EOL;
        $errorMessage .= 'Previous release not compatible with current DB Schema' . PHP_EOL;
        $errorMessage .= 'You can still do a manual rollback at your own risk' . PHP_EOL;
        throw new Exception($errorMessage);
    }

    writeln('Validation successful: Previous release is compatible with DB Schema');
});
