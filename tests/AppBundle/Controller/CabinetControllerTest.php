<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\User\User;

class CabinetControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    parent::setUpFixtures();

    $this->addFixture(new User());
  }

  public function testIndexAction()
  {
    $client = $this->getClient();

    $client->request('GET', '/lk');

    $this->assertTrue(
      $client->getResponse()->isRedirect('/login'),
      'Страница личного кабинета перенаправляет на форму входа, если не авторизован'
    );

    $this->logIn($this->getReference('user-simple'));
    $client->request('GET', '/lk');

    $this->assertTrue(
      $client->getResponse()->isSuccessful(),
      'Страница личного кабинета открывается, если пользователь авторизован'
    );

  }
}
