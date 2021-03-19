<?php
namespace Deployer;

use function Deployer\Support\array_to_string;

require 'recipe/symfony3.php';
require 'recipe/rsync.php';

function dockerGetContainerId($service)
{
  return run('cd {{release_path}}/laradock && docker-compose ps -q '.$service);
}

function dockerServiceExists($service)
{
  return !!dockerGetContainerId($service);
}

function dockerRemoveService($service)
{
  $containerId = dockerGetContainerId($service);

  if (!$containerId)
  {
    throw new \InvalidArgumentException(`Container ${service} not found.`);
  }

  return run('docker rm -f '.$containerId);
}

/**
 * Run a command using service, specified in docker-compose.yml file
 *
 * @param $service
 * @param $command
 * @param array $options
 */
function runInDocker($service, $command, $options = [])
{
  $containerId = dockerGetContainerId($service);

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

  $opts = [];
  if (isset($options['user']))
  {
    $opts[] = '-u '.$options['user'];
  }

  $command = sprintf('docker exec %s %s sh -c "%s"', implode(' ', $opts), $containerId, $command);

  return run($command);
}

// Project name
set('application', 'bezbahil');

// Project repository
set('repository', 'git@git.accurateweb.ru:accurateweb/bezbahil.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', [
  '.env', // Symfony app env
  'web/.htaccess',
//  'web/ajax/for_admin/import/log_cmo.txt',
//  'web/ajax/for_admin/import/log_mo.txt',
//  'web/bitrix/modules/updater.log',
//  'web/bitrix/modules/updater_partner.log',
//  'web/bitrix/php_interface/dbconn.php',
//  'web/bitrix/.settings.php',
//  'web/bitrix/site_checker_797f0a4ef69e8b269ee562becb5b10e5.log',
//  'web/bitrix/site_checker_afb111f71ba090216f391e97d08677c8.log',
  'web/cert1.pem',
  'web/export_2.csv',
  'web/export_f1111.csv',
  'laradock/.env', // Laradock build env
  'laradock/nginx/sites/bezbahil.ru.conf',
  'laradock/nginx/sites/devdoc1.kdteam.su.conf',
//  'laradock/php-fpm-bitrix/msmtprc',
  'web/robots.txt',
  'web/sitemap.xml',
  'web/ssh/send_mail_after_30_day.txt',
//  'web/symfony-integration/config_rabbitmq.php',
]);

add('shared_dirs', [
  'laradock/certbot/certs',
//  'web/bitrix/backup',
//  'web/bitrix/cache',
//  'web/bitrix/managed_cache',
//  'web/bitrix_logs',
  'web/images',
  'web/upload',
  'var/uploads', //sf app uploads private
  'web/uploads',  //sf app uploads public
  'var/synchronization'  //sf app synchronization private
]);

// Writable dirs by web server
add('writable_dirs', [
//  'web/bitrix/cache',
//  'web/bitrix/managed_cache',
  'web/upload',
//  'web/vendor/mpdf/mpdf/tmp'
]);

set('allow_anonymous_stats', false);

// Workspace container service name
set('workspace_service', 'workspace');

// App path inside docker conatiner
set('docker_deploy_path', '/var/www');

// Which services to start after deploy
set('docker_start_services', [
  'percona',
  'php-fpm-symfony',
  'php-fpm-bitrix',
  'nginx',
  'rabbitmq',
  'php-rabbimq-consumer-obrashscheniya-files',
  'php-rabbimq-consumer-obrashscheniya-emails'
]);

// Composer уже установлен в контейнере проекта
set('bin/composer', 'composer');

// Composer уже установлен в контейнере проекта
set('bin/php', 'php');

set('bin/console', '{{docker_deploy_path}}/bin/console');

set('http_user', 'deployer');

// Hosts

//host('staging_host')
//    ->hostname('192.168.1.11')
//    ->stage('staging_host')
//    ->user('deployer')
//    ->set('deploy_path', '/var/www/sites/bezbahil');

//host('staging_workspace')
//  ->hostname('staging.aw-dev.ru')
//  ->port(2232)
//  ->stage('staging_workspace')
//  ->user('root')
//  ->set('http_user', 'root')
//  ->set('writable_use_sudo', false)
//  ->set('writable_mode', 'chmod')
//  ->set('deploy_path', '/var/www');

host('staging')
  ->hostname('staging.aw-dev.ru')
//  ->port(2222)
  ->stage('staging')
  ->user('deployer')
  ->set('docker_start_services', [
    'percona',
    'rabbitmq',
    'php-rabbimq-consumer-obrashscheniya-emails',
    'php-rabbimq-consumer-obrashscheniya-files',
    'php-fpm-symfony',
    'php-fpm-bitrix',
    'nginx',
    'maildev',
  ])
  ->set('keep_releases', 2)
  ->set('deploy_path', '/var/www/sites/bezbahil');

host('prod')
  ->hostname('84.201.185.203')
  ->port(2232)
  ->stage('prod')
  ->user('deployer')
  ->set('deploy_path', '/var/www/bezbahilru');

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

set('rsync_src', __DIR__);
set('rsync_dest', '{{release_path}}');

set('rsync', array_merge(get('rsync'), [
    'include' => [
      'web/',
      'web/local/',
      'web/local/templates/',
      'web/local/templates/kdteam/',
      'web/local/templates/kdteam/js',
      'web/local/templates/kdteam/js/***',
      'web/local/templates/kdteam/styles',
      'web/local/templates/kdteam/styles/***',
      'web/local/templates/kdteam/pages',
      'web/local/templates/kdteam/pages/***',
      'web/dist/',
      'web/dist/***'
    ],
    'exclude' => [
      '*'
    ],
    'flags' => 'rzv',
    'options' => [
      //No delete
    ]
]));

task('deploy', [
  'deploy:info',
  'deploy:prepare',
  'deploy:lock',
  'deploy:release',
  'deploy:update_code',
  'deploy:clear_paths',
  'deploy:create_cache_dir',
  'deploy:shared',
  'deploy:assets',
  'prepare_workspace',
//  'deploy:upload_artifacts',
  'rsync', //rsync artifacts
  'deploy:docker:vendors',
  'deploy:docker:vendors_bitrix',
  'deploy:docker:assets:install',
  'deploy:docker:cache:clear',
  'deploy:docker:cache:warmup',
//  'deploy:writable',
  'deploy:docker:database:migrate',
  'deploy:docker:rabbitmq:setup_fabric',
  'deploy:symlink',
  'deploy:unlock',
  'start_services',
  'cleanup',
])->desc('Deploy your project');

/**
 * Install assets from public dir of bundles
 */
task('deploy:docker:assets:install', function () {
  runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} assets:install {{console_options}} {{docker_deploy_path}}/web', [
    'user' => 'laradock'
  ]);
})->desc('Install bundle assets');


/**
 * Dump all assets to the filesystem
 */
task('deploy:docker:assetic:dump', function () {
  if (get('dump_assets')) {
    runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} assetic:dump {{console_options}}', [
      'user' => 'laradock'
    ]);
  }
})->desc('Dump assets');

/**
 * Clear Cache
 */
task('deploy:docker:cache:clear', function () {
  runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} cache:clear {{console_options}} --no-warmup', [
    'user' => 'laradock'
  ]);
})->desc('Clear cache');

/**
 * Warm up cache
 */
task('deploy:docker:cache:warmup', function () {
  runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} cache:warmup {{console_options}}', [
    'user' => 'laradock'
  ]);
})->desc('Warm up cache');


/**
 * Migrate database
 */
task('deploy:docker:database:migrate', function () {
  $options = '{{console_options}} --allow-no-migration';
//  if (get('migrations_config') !== '') {
//    $options = sprintf('%s --configuration={{release_path}}/{{migrations_config}}', $options);
//  }

  runInDocker(get('workspace_service'), sprintf('{{bin/php}} {{bin/console}} doctrine:migrations:migrate %s', $options));
})->desc('Migrate database');

/**
 * Install assets from public dir of bundles
 */
task('deploy:docker:rabbitmq:setup_fabric', function () {
  runInDocker(get('workspace_service'), '{{bin/php}} {{bin/console}} rabbitmq:setup-fabric {{console_options}}', [
    'user' => 'laradock'
  ]);
})->desc('Setup RabbitMQ fabric');

desc('Installing vendors');
task('deploy:docker:vendors', function () {
  runInDocker(get('workspace_service'), '{{bin/composer}} {{composer_options}}', [
    'user' => 'laradock'
  ]);
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
task('prepare_workspace', function(){
  $workspaceService = get('workspace_service');

  if (dockerServiceExists($workspaceService))
  {
    dockerRemoveService($workspaceService);
  }

  run('cd {{release_path}}/laradock && docker-compose up -d '.$workspaceService, ['env' => [
    'APP_CODE_PATH_HOST' => get('release_path')
  ]]);
  // fix ssh files owner
  //run('cd {{release_path}}/laradock && docker-compose exec --user=laradock workspace sh -c "chown -R root:root /root/.ssh"');
})->desc('Preparing workspace container');

task('start_services', function(){
  $services = get('docker_start_services');

  run('cd {{current_path}}/laradock && docker-compose up -d '.implode(' ', $services));
});

desc('Installing vendors for Bitrix');
task('deploy:docker:vendors_bitrix', function()
{
  if (!commandExist('unzip'))
  {
    writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension https://goo.gl/sxzFcD</comment>');
  }
  runInDocker('workspace', 'cd {{docker_deploy_path}}/web && {{bin/composer}} {{composer_options}}', [
    'user' => 'laradock'
  ]);
});

