<?php

namespace Tests\AppBundle\Controller\Cabinet;

use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\User\User;

class AppealsListTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new User());
  }

  public function testDenied ()
  {
    $this->getClient()->request('GET', '/lk/my-appeals');
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/login/', $this->getClient()->getResponse()->headers->get('Location'));

    $this->getClient()->request('GET', '/api/v1/lk/my-appeals');
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/login/', $this->getClient()->getResponse()->headers->get('Location'));
  }

  public function  testOk ()
  {
    $this->logIn();
    $this->getClient()->request('GET', '/lk/my-appeals');
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());

    $this->getClient()->request('GET', '/api/v1/lk/my-appeals');
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }
}