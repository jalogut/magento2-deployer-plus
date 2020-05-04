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

/*
 * By default, cache enabling is disabled.
 */
set('cache_enabled_caches', '');

/*
 * To enable all caches after deployment, configure the following:
 */
//set('cache_enabled_caches', 'all');
 

/*
 * One can provide specific caches as well.
 */
/*set('cache_enabled_caches',
    [
        'config',
        'layout',
        'block_html',
        'collections',
        'reflection',
        'db_ddl',
        'eav',
        'customer_notification',
        'full_page',
        'config_integration',
        'config_integration_api',
        'translate',
        'config_webservice',
        'compiled_config',
    ]
);
 */

task('cache:enable', function () {
    $enabledCaches = get('cache_enabled_caches');
    
    if (empty($enabledCaches)) {
        return;
    }

    $command = '{{bin/php}} {{release_path}}/{{magento_bin}} cache:enable';

    if ($enabledCaches === 'all') {
        run($command);
    }

    if (is_array($enabledCaches)) {
        run($command . ' ' . implode(' ', $enabledCaches));
    }
});
