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

  public function testRegionList()
  {
    $client = static::createClient();

    /**
     * Тест открытия страницы
     */
    $client->request('GET', '/api/v1/regions');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());

    $res = $client->getResponse();
    $content = !empty($res) ? $res->getContent() : null;
    $content = !empty($content) ? json_decode($content, true) : null;

    // Проверка, что данные не пустые
    $this->assertTrue(is_array($content) && count($content) > 0);

    /**
     * Проверка, через поиск региона, который был добавлен первым,
     * так как в последствии в тесты будут добавлены еще регионы и не можем сейчас проверять их кол-во
     */
    $isSearch = false;
    foreach ($content['regions'] as $region)
    {
      if ($region['name'] === 'Свердловская область')
      {
        $isSearch = true;
      }
    }
    $this->assertTrue($isSearch);
  }
}
