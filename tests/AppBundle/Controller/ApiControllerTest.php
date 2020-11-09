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
  }
}
