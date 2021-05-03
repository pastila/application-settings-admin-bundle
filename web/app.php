<?php

use AppBundle\Dotenv\Dotenv;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';
if (PHP_VERSION_ID < 70000) {
    include_once __DIR__.'/../var/bootstrap.php.cache';
}

if (class_exists(Dotenv::class) && is_file(dirname(__DIR__) . '/.env'))
{
  // load all the .env files
  (new Dotenv())->loadEnv(dirname(__DIR__) . '/.env');
}

$env = isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : 'prod';
$debug = isset($_SERVER['APP_DEBUG']) && $_SERVER['APP_DEBUG'] === 'true' ? true : false;

//if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false)
//{
//  Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
//}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false)
{
  Request::setTrustedHosts([$trustedHosts]);
}

if ($debug === true)
{
  Debug::enable();
}

$kernel = new AppKernel($env, $debug);

if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}

//$kernel = new AppCache($kernel);

Request::setTrustedProxies(
// the IP address (or range) of your proxy
  [
    '172.18.0.0/24', '192.168.1.6', // Accurateweb Staging
    // ... for production = ?
  ],
// trust *all* "X-Forwarded-*" headers
  Request::HEADER_X_FORWARDED_ALL
);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
