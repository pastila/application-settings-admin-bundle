<?php

namespace Tests\AppBundle\Controller\Appeal;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Entity\User\Patient;
use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequest;
use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequestManager;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\OmsChargeComplaint\OmsChargeComplaintFixture;

class SeventhStepTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new OmsChargeComplaintFixture());
  }

  public function testUnauth ()
  {
    $sixthStep = $this->getReference('oms-charge-complaint-6th');
    $this->setToResolver($sixthStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/login/', $this->getClient()->getResponse()->headers->get('Location'));
  }

  public function testRedirect ()
  {
    $this->logIn();
    $sixthStep = $this->getReference('oms-charge-complaint-6th');
    $this->setToResolver($sixthStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/step-6/', $this->getClient()->getResponse()->headers->get('Location'));
  }

  public function testOk ()
  {
    $this->logIn();
    $seventhStep = $this->getReference('oms-charge-complaint-7th');
    $this->setToResolver($seventhStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testRedirectAfterComplete ()
  {
    $this->logIn();
    $seventhStep = $this->getReference('oms-charge-complaint-7th');
    $this->setToResolver($seventhStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());

    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertSame(OmsChargeComplaint::STATUS_CREATED, $seventhStep->getStatus());
  }

  private function getUrl ()
  {
    return '/oms-charge-complaint/step-7';
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

  private function setPhoneVerificationRequest (PhoneVerificationRequest $phoneVerificationRequest)
  {
    $manager = $this->createMock(PhoneVerificationRequestManager::class);
    $this->getClient()->getContainer()->set(PhoneVerificationRequestManager::class, $manager);
    $manager->method('getVerificationRequest')->willReturn($phoneVerificationRequest);
//    $this->getSession()->set('PHONE_VERIFICATION_REQUEST', serialize($phoneVerificationRequest));
  }
}