<?php

namespace Tests\AppBundle\Controller\Appeal;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\OmsChargeComplaint\OmsChargeComplaintFixture;

class FifthStepTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new OmsChargeComplaintFixture());
  }

  public function testRedirect ()
  {
    $fourthStep = $this->getReference('oms-charge-complaint-4th');
    $this->setToResolver($fourthStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testOk ()
  {
    $fifthStep = $this->getReference('oms-charge-complaint-5th');
    $this->setToResolver($fifthStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testLoginForm ()
  {
    $fifthStep = $this->getReference('oms-charge-complaint-5th');
    $this->setToResolver($fifthStep);
    $crawler = $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
    $this->assertCount(1, $crawler->filter('form'));
  }

  public function testAuthenticatedHasNotForm ()
  {
    $this->logIn();
    $fifthStep = $this->getReference('oms-charge-complaint-5th');
    $this->setToResolver($fifthStep);
    $crawler = $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
    $this->assertCount(0, $crawler->filter('form'));
  }

  private function getUrl ()
  {
    return '/oms-charge-complaint/step-5';
  }

  /**
   * @param OmsChargeComplaint $chargeComplaint
   */
  private function setToResolver (OmsChargeComplaint $chargeComplaint)
  {
    if (!$chargeComplaint->getId())
    {
      throw new \Exception(sprintf('Oms Charge Complaint should be persisted'));
    }

    $this->getSession()->set('oms_charge_complaint_id', $chargeComplaint->getId());
  }
}