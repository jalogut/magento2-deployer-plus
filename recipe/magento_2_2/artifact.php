<?php

declare(strict_types=1);

/* (c) Juan Alonso <juan.jalogut@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

use Deployer\Exception\Exception;
use Deployer\Exception\GracefulShutdownException;
use function Deployer\Support\str_contains;

set('artifact_base_name', 'artifact');
set('artifact_dir', 'artifacts');
set('artifact_excludes_file', __DIR__ . '/../../config/artifact.excludes');
set('artifact_file', function(){
    return get('artifact_name').'.tgz';
});
set('artifact_path', function () {
    return get('artifact_dir') . '/' . get('artifact_file');
});

set('artifact_name', function () {
    // Same as artifact:info logic
    $name = get('artifact_base_name');
    $parts = array();

    $branch = get('branch');
    if (!empty($branch)) {
        $parts[] = "$branch";
    }

    if (input()->hasOption('tag') && !empty(input()->getOption('tag'))) {
        $tag = input()->getOption('tag');
        $parts[] = "tag_$tag";
    } elseif (input()->hasOption('revision') && !empty(input()->getOption('revision'))) {
        $revision = input()->getOption('revision');
        $parts[] = "rev_$revision";
    }

    if (count($parts)==0) {
        $date = run('date +"%Y-%m-%d_%H-%M-%S"');
        $parts[] = "HEAD_".$date;
    }

    return $name.'-'.implode('-', $parts);
});

task('artifact:info', function () {
    $what = '';
    $branch = get('branch');

    if (!empty($branch)) {
        $what = "<fg=magenta>$branch</fg=magenta>";
    }

    if (input()->hasOption('tag') && !empty(input()->getOption('tag'))) {
        $tag = input()->getOption('tag');
        $what = "tag <fg=magenta>$tag</fg=magenta>";
    } elseif (input()->hasOption('revision') && !empty(input()->getOption('revision'))) {
        $revision = input()->getOption('revision');
        $what = "revision <fg=magenta>$revision</fg=magenta>";
    }

    if (empty($what)) {
        $what = "<fg=magenta>HEAD</fg=magenta>";
    }

    writeln("Building $what : {{artifact_name}}");
})
    ->shallow()
    ->setPrivate();

desc('Preparing things for build');
task('artifact:prepare', function () {
    // Check if shell is POSIX-compliant
    $result = run('echo $0');

    if (!str_contains($result, 'bash') && !str_contains($result, 'sh')) {
        throw new \RuntimeException(
            'Shell on your server is not POSIX-compliant. Please change to sh, bash or similar.'
        );
    }

    run('if [ ! -d {{deploy_path}} ]; then mkdir -p {{deploy_path}}; fi');

    // Create metadata .dep dir.
    run("cd {{deploy_path}} && if [ ! -d .dep ]; then mkdir .dep; fi");

    // Create releases dir.
    run("cd {{deploy_path}} && if [ ! -d releases ]; then mkdir releases; fi");

    // Create artifacts dir.
    run("cd {{deploy_path}} && if [ ! -d {{artifact_dir}} ]; then mkdir {{artifact_dir}}; fi");
});

task('artifact:package', function(){
    run('tar --exclude-from={{artifact_excludes_file}} -czf {{artifact_path}} -C {{release_path}} .');
});

task('artifact:check', function(){

    $hasFile = (input()->hasOption('file') && !empty(input()->getOption('file')));
    $hasTag = (input()->hasOption('tag') && !empty(input()->getOption('tag')));
    $hasRevision = (input()->hasOption('revision') && !empty(input()->getOption('revision')));

    if ($hasFile) {
        $file = input()->getOption('file');
        set('artifact_path', $file);
        set('artifact_file', run("BASENAME ${$file}"));

    } else if($hasTag || $hasRevision) {
        // artifact_path computed automatically from options by future pattern
    } else{
        throw new GracefulShutdownException(
            "Please specify -file or [-branch] -tag / -revision.\n"
        );
    }

    $file = testLocally("[ -f {{artifact_path}} ]");

    if (!$file) {
        $artifact_name = get('artifact_name');
        $artifact_path = get('artifact_path');
        throw new GracefulShutdownException(
            "Artifact to deploy ($artifact_name) doesnt exist at path ($artifact_path).\n"
        );
    }
});

task('artifact:upload', function () {
    upload(get('artifact_path'), '{{release_path}}');
});

task('artifact:extract', '
	tar -xzf {{release_path}}/{{artifact_file}} -C {{release_path}};
	rm -rf {{release_path}}/{{artifact_file}}
');
