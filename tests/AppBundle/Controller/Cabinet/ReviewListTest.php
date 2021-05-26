<?php

namespace Tests\AppBundle\Controller\Cabinet;

use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Company\Feedback;

class ReviewListTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new Feedback());
  }

  public function testAccessDenied ()
  {
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/login/', $this->getClient()->getResponse()->headers->get('Location'));
  }
  public function testOk ()
  {
    $this->logIn();
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  private function getUrl ()
  {
    return '/lk/my-reviews';
  }
}