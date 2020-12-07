<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Geo\Region;
use Tests\AppBundle\Fixtures\News\News;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Fixtures\Question\Question;

class QuestionsControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new Region());
    $this->addFixture(new Question());
  }

  public function testIndex()
  {
    $client = static::createClient();

    /**
     * Проверка, что страница открывается
     */
    $client->request('GET', '/vopros-otvet');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
  }
}