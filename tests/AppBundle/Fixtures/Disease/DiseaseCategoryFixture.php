<?php

namespace Tests\AppBundle\Fixtures\Disease;

use AppBundle\Entity\Disease\DiseaseCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DiseaseCategoryFixture extends Fixture
{
  public function load (ObjectManager $manager)
  {
    $root = new DiseaseCategory();
    $root->setTreeLevel(0);
    $root->setName('Root');

    $cat1 = new DiseaseCategory();
    $cat1->setName('Category');
    $cat1->setParent($root);

    $cat2 = new DiseaseCategory();
    $cat2->setParent($cat1);
    $cat2->setName('Disease Category');

    $cat3 = new DiseaseCategory();
    $cat3->setParent($cat2);
    $cat3->setName('Bolezni');

    $manager->persist($root);
    $manager->persist($cat1);
    $manager->persist($cat2);
    $manager->persist($cat3);
    $manager->flush();
    $this->setReference('disease-category-3', $cat3);
  }

}