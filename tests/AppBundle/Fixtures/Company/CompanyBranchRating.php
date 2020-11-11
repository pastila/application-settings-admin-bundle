<?php


namespace Tests\AppBundle\Fixtures\Company;


use AppBundle\Entity\Company\CompanyStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\Geo\Region;

class CompanyBranchRating extends Fixture implements DependentFixtureInterface
{
  /**
   * @return array
   */
  function getDependencies()
  {
    return [
      CompanyRating::class,
      Region::class
    ];
  }

  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $akbars66 = new \AppBundle\Entity\Company\CompanyBranch();
    $akbars66
      ->setCompany($this->getReference('akbars'))
      ->setRegion($this->getReference('region-66'))
      ->setName('АКБАРС-МЕД')
      ->setValuation(3.0)
      ->setStatus(CompanyStatus::ACTIVE);
    $manager->persist($akbars66);
    $manager->flush();

    $this->addReference('akbars-66', $akbars66);
  }
}