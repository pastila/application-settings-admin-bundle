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
      ->setValuation(4.5)
      ->setStatus(CompanyStatus::ACTIVE);
    $manager->persist($sogazMed);

    $akbars = new \AppBundle\Entity\Company\Company();
    $akbars
      ->setKpp('1041625409033')
      ->setName('АКБАРС-МЕД')
      ->setValuation(3.0)
      ->setStatus(CompanyStatus::ACTIVE);
    $manager->persist($akbars);

    $ingostach_m = new \AppBundle\Entity\Company\Company();
    $ingostach_m
      ->setKpp('1045207042528')
      ->setName('ИНГОССТРАХ-М')
      ->setValuation(5.0)
      ->setStatus(CompanyStatus::ACTIVE);
    $manager->persist($ingostach_m);

    $manager->flush();

    $this->addReference('sogaz-med', $sogazMed);
    $this->addReference('akbars', $akbars);
    $this->addReference('ingostach_m', $ingostach_m);
  }
}