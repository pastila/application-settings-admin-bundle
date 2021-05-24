<?php

namespace Tests\AppBundle\Fixtures\Organization;

use AppBundle\Entity\Organization\MedicalOrganization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\Geo\Region;

class MedicalOrganizationFixture extends Fixture implements DependentFixtureInterface
{
  public function load (ObjectManager $manager)
  {
    $organization = new MedicalOrganization();
    $organization
      ->setRegion($this->getReference('region-66'))
      ->setPublished(true)
      ->setAddress('Yekaterinburg, Turgeneva 18')
      ->setName('Medical Organization')
      ->setFullName('Full Medical Organization')
      ->setCode(1234)
    ;

    $this->setReference('medical-organization-1', $organization);
    $manager->persist($organization);
    $manager->flush();
  }

  public function getDependencies ()
  {
    return [
      Region::class,
    ];
  }


}