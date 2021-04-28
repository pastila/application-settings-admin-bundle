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
  public function testAppealDownload()
  {
    $client = static::createClient();

    /**
     * Проверка, что при открытии страницы у не авторизованного пользователя происходит редирект
     */
    $client->request('GET', '/appeals/61805/download');
    $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'Не авторизованный пользователь не был перенаправлен с 302 статусом');
  }

  public function testIndexLegacy()
  {
    $client = static::createClient();

    $client->request('GET', '/forma-obrasheniya/');

    $response = $client->getResponse();

    $this->assertEquals(301, $response->getStatusCode(), 'Legacy chargeback form redirects to new appeal form with status code 301');
    $this->assertEquals('/oms-charge-complaint', $response->headers->get('Location'), 'Legacy chargeback form redirects to new form');
  }

  public function testIndex()
  {
    $client = static::createClient();

    $client->request('GET', '/oms-charge-complaint');

    $response = $client->getResponse();

    $this->assertTrue($response->isSuccessful(), 'Charge complaint form index page');

    $client->request('POST', '/oms-charge-complaint');

    $this->assertSame($response->isSuccessful(), 'Empty form returns 400');
  }
}
