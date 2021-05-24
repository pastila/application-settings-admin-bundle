<?php

namespace Tests\AppBundle\Controller\Appeal;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\OmsChargeComplaint\OmsChargeComplaintFixture;

class SecondStepTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new OmsChargeComplaintFixture());
  }

  public function testRedirect ()
  {
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testOk ()
  {
    $secondStep = $this->getReference('oms-charge-complaint-2nd');
    $this->setToResolver($secondStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testSubmit ()
  {
    $secondStep = $this->getReference('oms-charge-complaint-2nd');
    $this->setToResolver($secondStep);
    $this->getClient()->request('POST', $this->getUrl(), [
      'oms_charge_complaint2nd_step' => [
        '_token' => $this->generateCsrfToken('oms_charge_complaint2nd_step'),
        'medicalOrganization' => $this->getReference('medical-organization-1')->getCode(),
      ],
    ]);

    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
  }

  private function getUrl ()
  {
    return '/oms-charge-complaint/step-2';
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