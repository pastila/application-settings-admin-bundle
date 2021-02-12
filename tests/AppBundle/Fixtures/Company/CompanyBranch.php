<?php


namespace Tests\AppBundle\Fixtures\Company;


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
    $sogazMed66 = new \AppBundle\Entity\Company\InsuranceCompanyBranch();
    $sogazMed66
      ->setCompany($this->getReference('sogaz-med'))
      ->setRegion($this->getReference('region-66'))
      ->setValuation(4.0)
      ->setPublished(true);
    $manager->persist($sogazMed66);

    $akbars66 = new \AppBundle\Entity\Company\InsuranceCompanyBranch();
    $akbars66
      ->setCompany($this->getReference('akbars'))
      ->setRegion($this->getReference('region-66'))
      ->setValuation(3.0)
      ->setPublished(true);
    $manager->persist($akbars66);

    $ingostach_m66 = new \AppBundle\Entity\Company\InsuranceCompanyBranch();
    $ingostach_m66
      ->setCompany($this->getReference('ingostach_m'))
      ->setRegion($this->getReference('region-66'))
      ->setValuation(5.0)
      ->setPublished(true);
    $manager->persist($ingostach_m66);

    $arsenal66 = new \AppBundle\Entity\Company\InsuranceCompanyBranch();
    $arsenal66
      ->setCompany($this->getReference('arsenal'))
      ->setRegion($this->getReference('region-66'))
      ->setValuation(3.0)
      ->setPublished(false);
    $manager->persist($arsenal66);

    $maksm66 = new \AppBundle\Entity\Company\InsuranceCompanyBranch();
    $maksm66
      ->setCompany($this->getReference('maksm'))
      ->setRegion($this->getReference('region-66'))
      ->setValuation(5.0)
      ->setPublished(true);
    $manager->persist($maksm66);

    $maksm47 = new \AppBundle\Entity\Company\InsuranceCompanyBranch();
    $maksm47
      ->setCompany($this->getReference('maksm'))
      ->setRegion($this->getReference('region-47'))
      ->setValuation(4.0)
      ->setPublished(true);
    $manager->persist($maksm47);

    $manager->flush();

    $this->addReference('sogaz-med-66', $sogazMed66);
    $this->addReference('akbars-66', $akbars66);
    $this->addReference('ingostach_m66', $ingostach_m66);
    $this->addReference('arsenal-66', $arsenal66);
    $this->addReference('maksm-66', $maksm66);
    $this->addReference('maksm-47', $maksm47);
  }
}