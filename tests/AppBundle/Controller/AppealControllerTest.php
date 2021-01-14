<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Geo\Region;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\AppBundle\Fixtures\User\User;

class AppealControllerTest extends AppWebTestCase
{
  protected $client = null;

  protected function setUpFixtures()
  {
    $this->addFixture(new User());
  }

  public function testIndex()
  {
    $this->client = static::createClient();

    /**
     * Проверка, что страница открывается
     */
    $this->client->request('GET', '/appeals/61805/download');
    $this->assertTrue($this->client->getResponse()->getStatusCode() != 500);
  }

  /**
   * @param User|null $user
   * @param @deprecated array|null $roles Второй параметр будет работать только для первого запроса, роли обновляются на каждый request из бд
   */
  protected function logIn($user = null)
  {
    if (!$user)
    {
      $user = $this->getReference('user-admin');
      $user->setIsAdmin(true);
    }

    $roles = $user->getRoles();
    $this->user = $user;

    $session = $this->getClient()->getContainer()->get('session');
    $firewallContext = 'main';

    $token = new UsernamePasswordToken($user, null, $firewallContext, $roles);
    $session->set('_security_' . $firewallContext, serialize($token));
    $session->save();

    $cookie = new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId());
    $this->getClient()->getCookieJar()->set($cookie);
    $this->getClient()->getContainer()->get('security.token_storage')->setToken($token);
  }
}