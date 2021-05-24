<?php

namespace Tests\AppBundle\Controller\Appeal;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\OmsChargeComplaint\OmsChargeComplaintFixture;

class ThirdStepTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new OmsChargeComplaintFixture());
  }

  public function testRedirect ()
  {
    $secondStep = $this->getReference('oms-charge-complaint-2nd');
    $this->setToResolver($secondStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testOk ()
  {
    $thirdStep = $this->getReference('oms-charge-complaint-3rd');
    $this->setToResolver($thirdStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testSubmit ()
  {
    $thirdStep = $this->getReference('oms-charge-complaint-3rd');
    $this->setToResolver($thirdStep);
    $this->getClient()->request('POST', $this->getUrl(), [
      'oms_charge_complaint3rd_step' => [
        '_token' => $this->generateCsrfToken('oms_charge_complaint3rd_step'),
        'urgent' => '1',
      ],
    ]);

    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
  }

  private function getUrl ()
  {
    return '/oms-charge-complaint/step-3';
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