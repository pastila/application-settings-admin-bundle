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

    $ingostach_m66 = new \AppBundle\Entity\Company\CompanyBranch();
    $ingostach_m66
      ->setCompany($this->getReference('ingostach_m'))
      ->setRegion($this->getReference('region-66'))
      ->setName('ИНГОССТРАХ-М')
      ->setValuation(5.0)
      ->setStatus(CompanyStatus::ACTIVE);
    $manager->persist($ingostach_m66);

    $manager->flush();

    $this->addReference('akbars-66', $akbars66);
    $this->addReference('ingostach_m66', $ingostach_m66);
  }
}