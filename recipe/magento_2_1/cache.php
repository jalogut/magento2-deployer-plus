<?php
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

set('cache_enabled_caches', 
    [
        'config',
        'layout',
        'block_html',
        'collections',
        'reflection',
        'db_ddl',
        'eav',
        'customer_notification',
        'target_rule',
        'full_page',
        'config_integration',
        'config_integration_api',
        'translate',
        'config_webservice',
        'compiled_config',
    ]
);

task('cache:enable', function () {
    $enabledCaches = get('cache_enabled_caches');
    
    if (!count($enabledCaches)) {
        return;
    }

    $implodedCaches = implode(' ', $enabledCaches);
    run(sprintf('{{bin/php}} {{magento_bin}} cache:enable %s', $implodedCaches));
});
