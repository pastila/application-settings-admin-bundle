<?php

namespace Tests\AppBundle\Controller\Appeal;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\OmsChargeComplaint\OmsChargeComplaintFixture;

class FourthStepTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new OmsChargeComplaintFixture());
  }

  public function testRedirect ()
  {
    $thirdStep = $this->getReference('oms-charge-complaint-3rd');
    $this->setToResolver($thirdStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testOk ()
  {
    $fourthStep = $this->getReference('oms-charge-complaint-4th');
    $this->setToResolver($fourthStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testSubmit ()
  {
    $fourthStep = $this->getReference('oms-charge-complaint-4th');
    $this->setToResolver($fourthStep);
    $this->getClient()->request('POST', $this->getUrl(), [
      'oms_charge_complaint4th_step' => [
        '_token' => $this->generateCsrfToken('oms_charge_complaint4th_step'),
        'disease' => $this->getReference('disease-prostuda')->getId(),
      ],
    ]);

    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
  }

  private function getUrl ()
  {
    return '/oms-charge-complaint/step-4';
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