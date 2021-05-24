<?php

namespace Tests\AppBundle\Fixtures\Disease;

use AppBundle\Entity\Disease\Disease;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DiseaseFixture extends Fixture implements DependentFixtureInterface
{
  public function load (ObjectManager $manager)
  {
    $disease = new Disease();
    $disease
      ->setCode(1234)
      ->setName('Простуда')
      ->setCategory($this->getReference('disease-category-3'))
    ;

    $manager->persist($disease);
    $manager->flush();

    $this->setReference('disease-prostuda', $disease);
  }

  public function getDependencies ()
  {
    return [
      DiseaseCategoryFixture::class,
    ];
  }


}