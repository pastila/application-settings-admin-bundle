<?php


namespace Tests\AppBundle\Fixtures\Company;


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
    $sogazMed = new \AppBundle\Entity\Company\InsuranceCompany();
    $sogazMed
      ->setKpp('1027739008440')
      ->setName('СОГАЗ-МЕД')
      ->setValuation(4.0)
      ->setPublished(true);
    $manager->persist($sogazMed);

    $akbars = new \AppBundle\Entity\Company\InsuranceCompany();
    $akbars
      ->setKpp('1041625409033')
      ->setName('АКБАРС-МЕД')
      ->setValuation(3.0)
      ->setPublished(false);
    $manager->persist($akbars);

    $ingostach_m = new \AppBundle\Entity\Company\InsuranceCompany();
    $ingostach_m
      ->setKpp('1045207042528')
      ->setName('ИНГОССТРАХ-М')
      ->setValuation(5.0)
      ->setPublished(true);
    $manager->persist($ingostach_m);

    $arsenal = new \AppBundle\Entity\Company\InsuranceCompany();
    $arsenal
      ->setKpp('1147746437343')
      ->setName('АРСЕНАЛМС')
      ->setValuation(3.0)
      ->setPublished(true);
    $manager->persist($arsenal);

    $maksm = new \AppBundle\Entity\Company\InsuranceCompany();
    $maksm
      ->setKpp('1027739099772')
      ->setName('МАКС-М')
      ->setValuation(3.0)
      ->setPublished(true);
    $manager->persist($maksm);

    $manager->flush();

    $this->addReference('sogaz-med', $sogazMed);
    $this->addReference('akbars', $akbars);
    $this->addReference('ingostach_m', $ingostach_m);
    $this->addReference('arsenal', $arsenal);
    $this->addReference('maksm', $maksm);
  }
}