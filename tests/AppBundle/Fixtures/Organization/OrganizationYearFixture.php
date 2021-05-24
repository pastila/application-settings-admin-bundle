<?php

namespace Tests\AppBundle\Fixtures\Organization;

use AppBundle\Entity\Organization\OrganizationYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrganizationYearFixture extends Fixture implements DependentFixtureInterface
{
  public function load (ObjectManager $manager)
  {
    $year = new OrganizationYear();
    $year->setYear(2020);
    $year->setOrganization($this->getReference('medical-organization-1'));

    $this->setReference('year-2020', $year);
    $manager->persist($year);
    $manager->flush();
  }

  public function getDependencies ()
  {
    return [
      MedicalOrganizationFixture::class,
    ];
  }


}