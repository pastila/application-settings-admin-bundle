<?php

namespace Tests\AppBundle\Controller\Cabinet;

use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Company\Feedback;

class ReviewShowTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new Feedback());
  }

  public function testAccessDenied ()
  {
    $review = $this->getReference('feedback-moderation-not');
    $this->getClient()->request('GET', $this->getUrl($review->getId()));
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/login/', $this->getClient()->getResponse()->headers->get('Location'));
  }
  public function testOk ()
  {
    $review = $this->getReference('feedback-moderation-not');
    $this->logIn($this->getReference('user-simple'));
    $this->getClient()->request('GET', $this->getUrl($review->getId()));
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testNotFound ()
  {
    $review = $this->getReference('feedback-moderation-not');
    $this->logIn($this->getReference('user-admin'));
    $this->getClient()->request('GET', $this->getUrl($review->getId()));
    $this->assertSame(404, $this->getClient()->getResponse()->getStatusCode());
  }

  private function getUrl ($id)
  {
    return sprintf('/lk/my-reviews/%s', $id);
  }
}