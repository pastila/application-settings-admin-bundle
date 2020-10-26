<?php


namespace Tests\AppBundle\Fixtures\Company;


use AppBundle\Entity\Company\CompanyStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Company extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return mixed
     */
    public function load(ObjectManager $manager)
    {
        $sogazMed = new \AppBundle\Entity\Company\Company();
        $sogazMed
           ->setKpp('1027739008440')
           ->setName('СОГАЗ-МЕД')
           ->setStatus(CompanyStatus::ACTIVE);

        $manager->persist($sogazMed);


        //....
        $manager->flush();

        $this->addReference('sogaz-med', $sogazMed);
    }

}