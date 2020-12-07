<?php

if (isset($_SERVER['HTTP_X_SF_SECRET']))
{
  trigger_error("X-SF-SECRET header found. Wrong upstream? Please, check your nginx config.", E_USER_ERROR);
  die;
}

use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';
if (PHP_VERSION_ID < 70000) {
    include_once __DIR__.'/../var/bootstrap.php.cache';
}

$kernel = new AppKernel('prod', false);
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}
//$kernel = new AppCache($kernel);

Request::setTrustedProxies(
// the IP address (or range) of your proxy
  ['192.168.123.180/29', '192.168.123.190/29', '192.168.123.200/28'],
// trust *all* "X-Forwarded-*" headers
  Request::HEADER_X_FORWARDED_ALL
);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
