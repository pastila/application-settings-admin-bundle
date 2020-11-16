<?php
namespace Deployer;

use function Deployer\Support\array_to_string;

require 'recipe/symfony3.php';

/**
 * Run a command using service, specified in docker-compose.yml file
 *
 * @param $service
 * @param $command
 * @param array $options
 */
function runInDocker($service, $command, $options = [])
{
  $containerId = run(`docker-compose ps -q ${service}`);

  if (!$containerId)
  {
    throw new \InvalidArgumentException(`Container ${service} not found.`);
  }

  $command = parse($command);
  $workingPath = get('working_path', '');

  if (!empty($workingPath)) {
    $command = "cd $workingPath && ($command)";
  }

  $env = get('env', []) + ($options['env'] ?? []);
  if (!empty($env)) {
    $env = array_to_string($env);
    $command = "export $env; $command";
  }

  $command = `docker exec ${containerId} sh -c "${command}"`;

  return run($command);
}

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@git.accurateweb.ru:accurateweb/bezbahil.git');

// [Optional] Allocate tty for git clone. Default value is false.
//set('git_tty', true);

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
  'web/upload',
  'web/vendor/mpdf/mpdf/tmp'
]);

set('allow_anonymous_stats', false);

// Workspace container service name
set('workspace_service', 'workspace');

// Hosts

host('staging_host')
    ->hostname('192.168.1.11')
    ->stage('staging_host')
    ->user('deployer')
    ->set('deploy_path', '/var/www/sites/bezbahil');

host('staging_workspace')
  ->hostname('staging.aw-dev.ru')
  ->port(2232)
  ->stage('staging_workspace')
  ->user('root')
  ->set('http_user', 'root')
  ->set('writable_use_sudo', false)
  ->set('writable_mode', 'chmod')
  ->set('deploy_path', '/var/www');

host('staging')
  ->hostname('staging.aw-dev.ru')
  ->port(2222)
  ->stage('staging')
  ->user('deployer')
  ->set('deploy_path', '/var/www/sites/bezbahil');

host('prod_host')
  ->hostname('84.201.185.203')
  ->port(2232)
  ->stage('prod_host')
  ->user('deployer')
  ->set('deploy_path', '/var/www/bezbahilru');

host('prod_workspace')
  ->hostname('84.201.185.203')
  ->stage('prod_workspace')
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
//after('deploy:symlink', 'start_services');

//before('deploy:symlink', 'database:migrate');

//task('prepare_workspace', [
//  'deploy:info',
//  'deploy:prepare',
//  'deploy:lock',
//  'deploy:release',
//  'deploy:update_code',
//  'deploy:clear_paths',
//  'deploy:create_cache_dir',
//  'deploy:shared',
//  'deploy:unlock'
//]);

task('deploy', [
  'deploy:info',
  'deploy:prepare',
  'deploy:lock',
  'deploy:release',
  'deploy:update_code',
  'deploy:clear_paths',
  'deploy:create_cache_dir',
  'deploy:shared',
  'start_workspace',
  'deploy:assets',
  'deploy:docker:vendors',
  'deploy:docker:vendors_bitrix',
  'deploy:docker:assets:install',
  'deploy:docker:cache:clear',
  'deploy:docker:cache:warmup',
  'deploy:docker:database:migrate',
  'deploy:writable',
  'deploy:symlink',
  'deploy:unlock',
  'start_services',
  'cleanup',
])->desc('Deploy your project');

/**
 * Install assets from public dir of bundles
 */
task('deploy:docker:assets:install', function () {
  runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} assets:install {{console_options}} {{release_path}}/web');
})->desc('Install bundle assets');


/**
 * Dump all assets to the filesystem
 */
task('deploy:docker:assetic:dump', function () {
  if (get('dump_assets')) {
    runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} assetic:dump {{console_options}}');
  }
})->desc('Dump assets');

/**
 * Clear Cache
 */
task('deploy:docker:cache:clear', function () {
  runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} cache:clear {{console_options}} --no-warmup');
})->desc('Clear cache');

/**
 * Warm up cache
 */
task('deploy:docker:cache:warmup', function () {
  runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} cache:warmup {{console_options}}');
})->desc('Warm up cache');


/**
 * Migrate database
 */
task('deploy:docker:database:migrate', function () {
  $options = '{{console_options}} --allow-no-migration';
  if (get('migrations_config') !== '') {
    $options = sprintf('%s --configuration={{release_path}}/{{migrations_config}}', $options);
  }

  runInDocker(get('workspace_service'), sprintf('{{bin/php}} {{bin/console}} doctrine:migrations:migrate %s', $options));
})->desc('Migrate database');

desc('Installing vendors');
task('deploy:docker:vendors', function () {
  runInDocker(get('workspace_service'), 'cd {{release_path}} && {{bin/composer}} {{composer_options}}');
});

desc('Fix file owner after build');
task('fix_owner', function(){
  $previousReleaseExist = test('[ -h release ]');

  if ($previousReleaseExist)
  {
    run('chown `whoami`:`whoami` $(readlink release)');
  }
});

// build and start the workspace container
task('start_workspace', function(){
  run('cd {{release_path}}/laradock && docker-compose up -d workspace');
  // fix ssh files owner
  run('cd {{release_path}}/laradock && docker exec $(docker-compose ps -q workspace) sh -c "chown -R root:root /root/.ssh"');
});

task('start_services', function(){
  run('cd {{current_path}}/laradock && docker-compose up -d');
});

desc('Installing vendors for Bitrix');
task('deploy:docker:vendors_bitrix', function()
{
  if (!commandExist('unzip'))
  {
    writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension https://goo.gl/sxzFcD</comment>');
  }
  runInDocker('php-fpm-bitrix', 'cd {{release_path}}/web && {{bin/composer}} {{composer_options}}');
});
