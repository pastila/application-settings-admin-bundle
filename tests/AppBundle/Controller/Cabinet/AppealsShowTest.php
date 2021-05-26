<?php

namespace Tests\AppBundle\Controller\Cabinet;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\OmsChargeComplaint\OmsChargeComplaintFixture;
use Tests\AppBundle\Fixtures\User\User;

class AppealsShowTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new User());
    $this->addFixture(new OmsChargeComplaintFixture());
  }

  public function testDenied ()
  {
    /** @var OmsChargeComplaint $appeal */
    $appeal = $this->getReference('oms-charge-complaint-created');
    $this->getClient()->request('GET', $this->getUrl($appeal->getId()));
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/login/', $this->getClient()->getResponse()->headers->get('Location'));

    $this->getClient()->request('GET', $this->getApiUrl($appeal->getId()));
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/login/', $this->getClient()->getResponse()->headers->get('Location'));
  }

  public function  testOk ()
  {
    $this->logIn();
    $appeal = $this->getReference('oms-charge-complaint-created');
    $this->getClient()->request('GET', $this->getUrl($appeal->getId()));
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());

    $this->getClient()->request('GET', $this->getApiUrl($appeal->getId()));
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testNotFound ()
  {
    $this->logIn();
    $appeal = $this->getReference('oms-charge-complaint-7th');
    $this->getClient()->request('GET', $this->getUrl($appeal->getId()));
    $this->assertSame(404, $this->getClient()->getResponse()->getStatusCode(), $this->getClient()->getResponse()->getContent());

    $this->getClient()->request('GET', $this->getApiUrl($appeal->getId()));
    $this->assertSame(404, $this->getClient()->getResponse()->getStatusCode());
  }

  private function getUrl ($id)
  {
    return sprintf('/lk/my-appeals/%s', $id);
  }

  private function getApiUrl ($id)
  {
    return sprintf('/api/v1/lk/my-appeals/%s', $id);
  }
}