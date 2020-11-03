<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;

class HomepageControllerTest extends AppWebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}