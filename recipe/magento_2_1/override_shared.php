<?php

declare(strict_types=1);

/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

use Deployer\Exception\Exception;

set('override_shared_dirs', []);
set('override_shared_files', []);

desc('Creating Override symlinks for override shared files and dirs');
task('deploy:override_shared', function () {
    $sharedPath = "{{deploy_path}}/shared";

    // Validate shared_dir, find duplicates
    foreach (get('override_shared_dirs') as $a) {
        foreach (get('override_shared_dirs') as $b) {
            if ($a !== $b && strpos(rtrim($a, '/') . '/', rtrim($b, '/') . '/') === 0) {
                throw new Exception("Can not share same dirs `$a` and `$b`.");
            }
        }
    }

    foreach (get('override_shared_dirs') as $dir) {
        // Check if shared dir exists.
        if (test("[ -d $sharedPath/$dir ]")) {
            // remove shared dir
            run("rm -rf $sharedPath/$dir");
        }
        // If release contains shared dir, copy that dir from release to shared.
        if (test("[ -d $(echo {{release_path}}/$dir) ]")) {
            run("cp -rv {{release_path}}/$dir $sharedPath/" . dirname(parse($dir)));
            run("rm -rf {{release_path}}/$dir");
        } else {
            // Create shared dir if it does not exist.
            run("mkdir -p $sharedPath/$dir");
        }

        // Create path to shared dir in release dir if it does not exist.
        // Symlink will not create the path and will fail otherwise.
        run("mkdir -p `dirname {{release_path}}/$dir`");

        // Symlink shared dir to release dir
        run("{{bin/symlink}} $sharedPath/$dir {{release_path}}/$dir");
    }

    foreach (get('override_shared_files') as $file) {
        $dirname = dirname(parse($file));

        // Create dir of shared file
        run("mkdir -p $sharedPath/" . $dirname);

        // Check if shared file exists in shared and remove it
        if (test("[ -f $sharedPath/$file ]")) {
            run("rm -rf $sharedPath/$file");
        }

        // If file exist in release
        if (test("[ -f {{release_path}}/$file ]")) {
            // Copy file in shared dir if not present
            run("cp -rv {{release_path}}/$file $sharedPath/$file");
            run("rm -rf {{release_path}}/$file");
        }

        // Ensure dir is available in release
        run("if [ ! -d $(echo {{release_path}}/$dirname) ]; then mkdir -p {{release_path}}/$dirname;fi");

        // Touch shared
        run("touch $sharedPath/$file");

        // Symlink shared dir to release dir
        run("{{bin/symlink}} $sharedPath/$file {{release_path}}/$file");
    }
});
