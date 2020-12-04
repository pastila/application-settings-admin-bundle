<?php

namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Geo\Region;

class ApiControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new Region());
  }

  /**
   * Проверка "Список регионов полученных по API":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-84
   */
  public function testRegionList()
  {
    $client = static::createClient();
    $textPrev = 'Список регионов полученных по API: ';

    /**
     * Тест открытия страницы
     */
    $client->request('GET', '/api/v1/regions');
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), $textPrev. 'Проверка, что данные получены без ошибок, статус 200');

    $res = $client->getResponse();
    $content = !empty($res) ? $res->getContent() : null;
    $content = !empty($content) ? json_decode($content, true) : null;
    $this->assertTrue(json_last_error() === JSON_ERROR_NONE, $textPrev . 'Проверка, что формат данных json');

    $this->assertTrue(is_array($content) && count($content) > 0, $textPrev . 'Проверка, что данные не пустые');
    $this->assertEquals('02 Республика Башкортостан', $content['regions'][0]['name'], 'Проверка, что Название совпадает');
    $this->assertEquals(1, $content['regions'][0]['id'], 'Проверка, что ID совпадает');
    $this->assertEquals('66 Свердловская область', $content['regions'][1]['name'], 'Проверка, что Название совпадает');
    $this->assertEquals(2, $content['regions'][1]['id'], 'Проверка, что ID совпадает');
  }
}