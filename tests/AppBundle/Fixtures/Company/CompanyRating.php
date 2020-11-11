<?php


namespace Tests\AppBundle\Fixtures\Company;


use AppBundle\Entity\Company\CompanyStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyRating extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $akbars = new \AppBundle\Entity\Company\Company();
    $akbars
      ->setKpp('1041625409033')
      ->setName('АКБАРС-МЕД')
      ->setValuation(3.0)
      ->setStatus(CompanyStatus::ACTIVE);
    $manager->persist($akbars);
    $manager->flush();

    $this->addReference('akbars', $akbars);
  }
}