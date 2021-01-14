<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\User\User;

class AppealControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new User());
  }

  /**
   * https://jira.accurateweb.ru/browse/BEZBAHIL-216
   */
  public function testIndex()
  {
    $client = static::createClient();

    /**
     * Проверка, что при открытии страницы у не авторизованного пользователя происходит редирект
     */
    $client->request('GET', '/appeals/61805/download');
    $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'Не авторизованный пользователь не был перенаправлен с 302 статусом');
  }
}