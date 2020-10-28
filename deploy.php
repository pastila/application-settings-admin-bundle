<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@git.accurateweb.ru:accurateweb/bezbahil.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', [
    'web/bitrix/cache',
    'web/bitrix/managed_cache'
]);

// Writable dirs by web server 
add('writable_dirs', [
    'web/bitrix/cache',
    'web/bitrix/managed_cache'
]);
set('allow_anonymous_stats', false);

// Hosts

host('192.168.1.11')
    ->stage('staging')
    ->set('deploy_path', '/var/www/sites/bezbahil/{{application}}');
    
// Tasks
task('update_services', function () {
    run('cd {{release_path}}/laradock && docker-compose -f docker-compose.staging.ymld up -d');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

