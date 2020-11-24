<?php


namespace Tests\AppBundle\Fixtures\Company;


use AppBundle\Entity\Company\CompanyStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\Geo\Region;

class CompanyBranch extends Fixture implements DependentFixtureInterface
{
  /**
   * @return array
   */
  function getDependencies()
  {
    return [
      Company::class,
      Region::class
    ];
  }

  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $sogazMed66 = new \AppBundle\Entity\Company\CompanyBranch();
    $sogazMed66
      ->setCompany($this->getReference('sogaz-med'))
      ->setRegion($this->getReference('region-66'))
      ->setName('СОГАЗ-МЕД')
      ->setValuation(4.5)
      ->setStatus(CompanyStatus::ACTIVE);
    $manager->persist($sogazMed66);

    $manager->flush();

    $this->addReference('sogaz-med-66', $sogazMed66);
  }

}