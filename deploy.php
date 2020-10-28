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
add('shared_files', [
  '.env',
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
  'laradock/.env',
  'laradock/certbot/certs/cert1.pem',
  'laradock/certbot/certs/chain1.pem',
  'laradock/certbot/certs/fullchain1.pem',
  'laradock/certbot/certs/privkey1.pem',
  'laradock/docker-compose.yml',
  'laradock/nginx/sites/bezbahil.ru.conf',
  'laradock/nginx/sites/devdoc1.kdteam.su.conf',
  'web/robots.txt',
  'web/sitemap.xml',
  'ssh/send_mail_after_30_day.txt',
]);

add('shared_dirs', [
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

host('192.168.1.11')
    ->stage('staging')
    ->set('deploy_path', '/var/www/sites/bezbahil/{{application}}');

host('84.201.185.203')
  ->stage('prod')
  ->user('root')
  ->set('deploy_path', '/var/www');

// Tasks
//task('update_services', function () {
//    run('cd {{release_path}}/laradock && docker-compose -f docker-compose.staging.yml up -d');
//});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

