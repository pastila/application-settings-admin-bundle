<?php
namespace Deployer;

require 'recipe/symfony3.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@git.accurateweb.ru:accurateweb/bezbahil.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', [
  '.env', // Symfony app env
  'web/.htaccess',
  'web/ajax/for_admin/import/log_cmo.txt',
  'web/ajax/for_admin/import/log_mo.txt',
  'web/bitrix/modules/updater.log',
  'web/bitrix/modules/updater_partner.log',
  'web/bitrix/php_interface/dbconn.php',
  'web/bitrix/.settings.php',
  'web/bitrix/site_checker_797f0a4ef69e8b269ee562becb5b10e5.log',
  'web/bitrix/site_checker_afb111f71ba090216f391e97d08677c8.log',
  'web/cert1.pem',
  'web/export_2.csv',
  'web/export_f1111.csv',
  'laradock/.env', // Laradock build env
  'laradock/docker-compose.yml',
  'laradock/nginx/sites/bezbahil.ru.conf',
  'laradock/nginx/sites/devdoc1.kdteam.su.conf',
  'laradock/php-fpm-bitrix/msmtprc',
  'web/robots.txt',
  'web/sitemap.xml',
  'web/ssh/send_mail_after_30_day.txt',
]);

add('shared_dirs', [
  'laradock/certbot/certs',
  'web/bitrix/backup',
  'web/bitrix/cache',
  'web/bitrix/managed_cache',
  'web/bitrix_logs',
  'web/images',
  'web/upload'
]);

// Writable dirs by web server
add('writable_dirs', [
    'web/bitrix/cache',
    'web/bitrix/managed_cache',
    'web/upload'
]);
set('allow_anonymous_stats', false);

// Hosts

host('staging_host')
    ->hostname('192.168.1.11')
    ->stage('staging_host')
    ->user('deployer')
    ->set('deploy_path', '/var/www/sites/bezbahil');

host('staging_workspace')
  ->hostname('192.168.1.11')
  ->port(2022)
  ->stage('staging_workspace')
  ->user('root')
  ->set('http_user', 'root')
  ->set('writable_use_sudo', false)
  ->set('writable_mode', 'chmod')
  ->set('deploy_path', '/var/www');


host('84.201.185.203')
  ->stage('prod')
  ->user('root')
  ->set('http_user', 'root')
  ->set('writable_use_sudo', false)
  ->set('writable_mode', 'chmod')
  ->set('deploy_path', '/var/www');

// Tasks
//task('update_services', function () {
//    run('cd {{release_path}}/laradock && docker-compose -f docker-compose.staging.yml up -d');
//});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
after('deploy:symlink', 'start_services');

//before('deploy:symlink', 'database:migrate');

task('prepare_workspace', [
  'deploy:info',
  'deploy:prepare',
  'deploy:lock',
  'deploy:release',
  'deploy:update_code',
  'deploy:clear_paths',
  'deploy:create_cache_dir',
  'deploy:shared',
  'deploy:unlock'
]);

// build and start the workspace container
task('start_workspace', function(){
  run('cd {{release_path}}/laradock && docker-compose up -d workspace');
});

task('start_services', function(){
  run('cd {{release_path}}/laradock && docker-compose up -d');
});

