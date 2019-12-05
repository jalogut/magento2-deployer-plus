<?php

declare(strict_types=1);

/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

use Deployer\Exception\RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;

const CONFIG_IMPORT_NEEDED_EXIT_CODE = 2;

set('config_import_needed', function () {
    try {
        run('{{bin/php}} {{release_path}}/{{magento_bin}} app:config:status');
    } catch (ProcessFailedException $e) {
        if ($e->getProcess()->getExitCode() == CONFIG_IMPORT_NEEDED_EXIT_CODE) {
            return true;
        }
        throw $e;
    } catch (RuntimeException $e) {
        if ($e->getExitCode() == CONFIG_IMPORT_NEEDED_EXIT_CODE) {
            return true;
        }
        throw $e;
    }
    return false;
});
