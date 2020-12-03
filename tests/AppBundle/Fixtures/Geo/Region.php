<?php


namespace Tests\AppBundle\Fixtures\Geo;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Region extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $region02 = new \AppBundle\Entity\Geo\Region();
    $region02->setCode(02);
    $region02->setName('02 Республика Башкортостан');
    $manager->persist($region02);

    $region66 = new \AppBundle\Entity\Geo\Region();
    $region66->setCode(66);
    $region66->setName('66 Свердловская область');
    $manager->persist($region66);

    $manager->flush();

    $this->addReference('region-02', $region02);
    $this->addReference('region-66', $region66);
  }
}