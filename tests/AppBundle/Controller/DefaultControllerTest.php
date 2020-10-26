<?php

namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Company\Feedback;

class DefaultControllerTest extends AppWebTestCase
{
    protected function setUpFixtures()
    {
        $this->addFixture(new Feedback());
    }

    public function testFeedbackList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/feedback');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
