<?php

namespace Tests\AppBundle\Controller\Appeal;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Entity\User\Patient;
use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequest;
use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequestManager;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\OmsChargeComplaint\OmsChargeComplaintFixture;

class SixthStepTest extends AppWebTestCase
{
  protected function setUpFixtures ()
  {
    parent::setUpFixtures();
    $this->addFixture(new OmsChargeComplaintFixture());
  }

  public function testUnauth ()
  {
    $fifthStep = $this->getReference('oms-charge-complaint-5th');
    $this->setToResolver($fifthStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/login/', $this->getClient()->getResponse()->headers->get('Location'));
  }

  public function testRedirect ()
  {
    $this->logIn();
    $fifthStep = $this->getReference('oms-charge-complaint-5th');
    $this->setToResolver($fifthStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/step-5/', $this->getClient()->getResponse()->headers->get('Location'));
  }

  public function testOk ()
  {
    $this->logIn();
    $sixthStep = $this->getReference('oms-charge-complaint-6th');
    $this->setToResolver($sixthStep);
    $this->getClient()->request('GET', $this->getUrl());
    $this->assertSame(200, $this->getClient()->getResponse()->getStatusCode());
  }

  public function testSubmitNewPatient ()
  {
    $this->logIn();
    $request = new PhoneVerificationRequest();
    $phone = '+7(999)999-999-99';
    $request->setPhone($phone);
    $request->setVerificationCode('123');
    $request->setCreatedAt(new \DateTime());
    $request->setVerificationCodeSentAt(new \DateTime());
    $this->setPhoneVerificationRequest($request);
    $sixthStep = $this->getReference('oms-charge-complaint-6th');
    $this->setToResolver($sixthStep);
    $this->getClient()->request('POST', $this->getUrl(), [
      'oms_charge_complaint6th_step' => [
        '_token' => $this->generateCsrfToken('oms_charge_complaint6th_step'),
        'paidAt' => '2021-05-21',
        'patient' => [
          'lastName' => 'LastName',
          'firstName' => 'FirstName',
          'middleName' => 'MiddleName',
          'birthDate' => '1994-05-21',
          'insurancePolicyNumber' => '123456789',
          'region' => $this->getReference('region-66')->getId(),
          'insuranceCompany' => $this->getReference('sogaz-med')->getId(),
          'phone' => $phone,
          'verificationCode' => '123',
        ],
      ],
    ]);

    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode(), $this->getClient()->getResponse()->getContent());
    $this->assertRegExp('/step-7/', $this->getClient()->getResponse()->headers->get('Location'));
  }

  public function testSubmitExistingPatient ()
  {
    $this->logIn($this->getReference('user-sogaz-med-66'));
    $sixthStep = $this->getReference('oms-charge-complaint-6th');
    /** @var Patient $patient */
    $patient = $this->getReference('patient-sogaz-med-66');
    $this->setToResolver($sixthStep);
    $this->getClient()->request('POST', $this->getUrl(), [
      'oms_charge_complaint6th_step' => [
        '_token' => $this->generateCsrfToken('oms_charge_complaint6th_step'),
        'paidAt' => '2021-05-21',
        'patient' => [
          'lastName' => $patient->getLastName(),
          'firstName' => $patient->getFirstName(),
          'middleName' => $patient->getMiddleName(),
          'birthDate' => '1994-05-21',
          'insurancePolicyNumber' => $patient->getInsurancePolicyNumber(),
          'region' => $this->getReference('region-66')->getId(),
          'insuranceCompany' => $this->getReference('sogaz-med')->getId(),
          'phone' => $patient->getPhone(),
        ],
      ],
    ]);

    $this->assertSame(302, $this->getClient()->getResponse()->getStatusCode());
    $this->assertRegExp('/step-7/', $this->getClient()->getResponse()->headers->get('Location'));
  }

  private function getUrl ()
  {
    return '/oms-charge-complaint/step-6';
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