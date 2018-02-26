<?php
/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

task('cache:clear:magento', '{{magento_bin}} cache:flush');

task('cache:clear', 'cache:clear:magento');