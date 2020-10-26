<?php


namespace Tests\AppBundle\Fixtures\Geo;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Region extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return mixed
     */
    public function load(ObjectManager $manager)
    {
        $region = new \AppBundle\Entity\Geo\Region();
        $region->setCode(66);
        $region->setName('Свердловская область');

        $manager->persist($region);
        $manager->flush();

        $this->addReference('region-66', $region);
    }

}