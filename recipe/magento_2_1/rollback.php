<?php
/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

use Deployer\Exception\Exception;

task('rollback:validate', function () {
    $errorMessage = 'Secure rollback not possible' . PHP_EOL;
    $errorMessage .= PHP_EOL;
    $errorMessage .= 'This Magento version has the following constraints:' . PHP_EOL;
    $errorMessage .= '- Not possible to know if previous release is compatible with current DB schema' . PHP_EOL;
    $errorMessage .= PHP_EOL;
    $errorMessage .= 'You can still do a manual rollback at your own risk' . PHP_EOL;
    throw new Exception($errorMessage);
});
