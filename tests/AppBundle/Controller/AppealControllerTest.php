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
     * Проверка, что при открытии страницы нет 500 ошибки
     */
    $client->request('GET', '/appeals/61805/download');
    $this->assertTrue($client->getResponse()->getStatusCode() != 500);
  }
}