<?php


namespace Tests\AppBundle;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\FixtureAwareWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class AppWebTestCase extends FixtureAwareWebTestCase
{
    /** @var Client */
    protected $client = null;
    /** @var \AppBundle\Entity\User\User */
    protected $user;

    protected function setUp ()
    {
        $this->client = $this->getClient(true);

        parent::setUp();

        $this->setUpFixtures();
        $this->executeFixtures();
    }

    protected function getClient($reload=false)
    {
        if (!$this->client || $reload)
        {
            $this->client = static::createClient();
        }

        return $this->client;
    }

  /**
   * @param null $user
   * @param null $roles
   */
    protected function logIn($user = null, $roles = null)
    {
      if (!$user)
      {
        $user = $this->getReference('user-admin');
      }
      if (!$roles)
      {
        $roles = $user->getRoles();
      }
      $this->user = $user;
      $session = $this->client->getContainer()->get('session');
      $firewallContext = 'main';
      $token = new UsernamePasswordToken($user, null, $firewallContext, $roles);
      $session->set('_security_' . $firewallContext, serialize($token));
      $session->save();
      $cookie = new Cookie($session->getName(), $session->getId());
      $this->client->getCookieJar()->set($cookie);
      $this->client->getContainer()->get('security.token_storage')->setToken($token);
    }

    /**
     * @return SessionInterface|null
     */
    protected function getSession ()
    {
      return $this->getClient()->getContainer()->get('session');
    }

  /**
   * @param $formName
   * @return string|null
   */
    protected function generateCsrfToken ($formName)
    {
      return (string)$this->getClient()->getContainer()->get('security.csrf.token_manager')->getToken($formName);
    }
}