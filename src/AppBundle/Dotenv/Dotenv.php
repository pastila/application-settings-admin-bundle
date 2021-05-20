<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Dotenv;

use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use function trigger_deprecation;

class Dotenv
{
  private $envKey;

  private $legacyDotenv;

  /**
   * @param string $envKey
   */
  public function __construct($envKey = 'APP_ENV')
  {
    if (\in_array($envKey = (string) $envKey, ['1', ''], true)) {
      trigger_deprecation('symfony/dotenv', '5.1', 'Passing a boolean to the constructor of "%s" is deprecated, use "Dotenv::usePutenv()".', __CLASS__);
//      $this->usePutenv = (bool) $envKey;
      $envKey = 'APP_ENV';
    }

    $this->envKey = $envKey;

    $this->legacyDotenv = new \Symfony\Component\Dotenv\Dotenv();
  }

  /**
   * Loads a .env file and the corresponding .env.local, .env.$env and .env.$env.local files if they exist.
   *
   * .env.local is always ignored in test env because tests should produce the same results for everyone.
   * .env.dist is loaded when it exists and .env is not found.
   *
   * @param string $path        A file to load
   * @param string $envKey|null The name of the env vars that defines the app env
   * @param string $defaultEnv  The app env to use when none is defined
   * @param array  $testEnvs    A list of app envs for which .env.local should be ignored
   *
   * @throws FormatException when a file has a syntax error
   * @throws PathException   when a file does not exist or is not readable
   */
  public function loadEnv(string $path, string $envKey = null, string $defaultEnv = 'dev', array $testEnvs = ['test']): void
  {
    $k = $envKey ?? $this->envKey;

    if (is_file($path) || !is_file($p = "$path.dist")) {
      $this->legacyDotenv->load($path);
    } else {
      $this->legacyDotenv->load($p);
    }

    if (null === $env = $_SERVER[$k] ?? $_ENV[$k] ?? null) {
      $this->legacyDotenv->populate([$k => $env = $defaultEnv]);
    }

    if (!\in_array($env, $testEnvs, true) && is_file($p = "$path.local")) {
      $this->legacyDotenv->load($p);
      $env = $_SERVER[$k] ?? $_ENV[$k] ?? $env;
    }

    if ('local' === $env) {
      return;
    }

    if (is_file($p = "$path.$env")) {
      $this->legacyDotenv->load($p);
    }

    if (is_file($p = "$path.$env.local")) {
      $this->legacyDotenv->load($p);
    }
  }
}
