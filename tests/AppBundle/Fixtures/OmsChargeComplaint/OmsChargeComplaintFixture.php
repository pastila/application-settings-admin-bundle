<?php

namespace Tests\AppBundle\Fixtures\OmsChargeComplaint;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\Company\Company;
use Tests\AppBundle\Fixtures\Disease\DiseaseFixture;
use Tests\AppBundle\Fixtures\Geo\Region;
use Tests\AppBundle\Fixtures\Organization\MedicalOrganizationFixture;
use Tests\AppBundle\Fixtures\Organization\OrganizationYearFixture;
use Tests\AppBundle\Fixtures\User\PatientFixture;

class OmsChargeComplaintFixture extends Fixture implements DependentFixtureInterface
{
  public function load (ObjectManager $manager)
  {
    $secondStep = new OmsChargeComplaint();
    $secondStep
      ->setDraftStep(2)
      ->setRegion($this->getReference('region-66'))
      ->setYear(2020);

    $thirdStep = new OmsChargeComplaint();
    $thirdStep
      ->setDraftStep(3)
      ->setRegion($this->getReference('region-66'))
      ->setYear(2020)
      ->setMedicalOrganization($this->getReference('medical-organization-1'))
    ;

    $fourthStep = new OmsChargeComplaint();
    $fourthStep
      ->setDraftStep(4)
      ->setRegion($this->getReference('region-66'))
      ->setYear(2020)
      ->setMedicalOrganization($this->getReference('medical-organization-1'))
      ->setUrgent(false);

    $fifthStep = new OmsChargeComplaint();
    $fifthStep
      ->setDraftStep(5)
      ->setRegion($this->getReference('region-66'))
      ->setYear(2020)
      ->setMedicalOrganization($this->getReference('medical-organization-1'))
      ->setUrgent(false)
      ->setDisease($this->getReference('disease-prostuda'))
    ;

    $sixthStep = new OmsChargeComplaint();
    $sixthStep
      ->setDraftStep(6)
      ->setRegion($this->getReference('region-66'))
      ->setYear(2020)
      ->setMedicalOrganization($this->getReference('medical-organization-1'))
      ->setUrgent(false)
      ->setDisease($this->getReference('disease-prostuda'))
    ;

    $seventhStep = new OmsChargeComplaint();
    $seventhStep
      ->setDraftStep(7)
      ->setRegion($this->getReference('region-66'))
      ->setYear(2020)
      ->setMedicalOrganization($this->getReference('medical-organization-1'))
      ->setUrgent(false)
      ->setDisease($this->getReference('disease-prostuda'))
      ->setPatient($this->getReference('patient-sogaz-med-66'))
      ->setPaidAt(new \DateTime('2020-01-20'))
//      ->addDocument()
    ;

    $manager->persist($secondStep);
    $manager->persist($thirdStep);
    $manager->persist($fourthStep);
    $manager->persist($fifthStep);
    $manager->persist($sixthStep);
    $manager->persist($seventhStep);
    $manager->flush();

    $this->setReference('oms-charge-complaint-2nd', $secondStep); //готовый к прохождению 2 шага
    $this->setReference('oms-charge-complaint-3rd', $thirdStep); //готовый к прохождению 3 шага
    $this->setReference('oms-charge-complaint-4th', $fourthStep); //готовый к прохождению 4 шага
    $this->setReference('oms-charge-complaint-5th', $fifthStep); //готовый к прохождению 5 шага
    $this->setReference('oms-charge-complaint-6th', $sixthStep); //готовый к прохождению 6 шага
    $this->setReference('oms-charge-complaint-7th', $seventhStep); //готовый к прохождению 7 шага
  }

  public function getDependencies ()
  {
    return [
      Region::class,
      MedicalOrganizationFixture::class,
      OrganizationYearFixture::class,
      DiseaseFixture::class,
      PatientFixture::class,
    ];
  }


}