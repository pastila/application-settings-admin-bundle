<?php

namespace Tests\AppBundle\Fixtures\User;

use AppBundle\Entity\User\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\Company\Company;
use Tests\AppBundle\Fixtures\Geo\Region;

class PatientFixture extends Fixture implements DependentFixtureInterface
{
  public function load (ObjectManager $manager)
  {
    $patient = new Patient();
    $patient
      ->setRegion($this->getReference('region-66'))
      ->setUser($this->getReference('user-sogaz-med-66'))
      ->setPhone('+7(999)123-45-67')
      ->setBirthDate(new \DateTime('1990-10-21'))
      ->setInsuranceCompany($this->getReference('sogaz-med'))
      ->setInsurancePolicyNumber('123456789')
      ->setFirstName('firstname')
      ->setLastName('lastname')
    ;

    $manager->persist($patient);
    $manager->flush();

    $this->setReference('patient-sogaz-med-66', $patient);
  }

  public function getDependencies ()
  {
    return [
      Region::class,
      User::class,
      Company::class,
    ];
  }


}