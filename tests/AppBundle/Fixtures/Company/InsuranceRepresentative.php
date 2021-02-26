<?php


namespace Tests\AppBundle\Fixtures\Company;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\Geo\Region;

/**
 * Class InsuranceRepresentative
 * @package Tests\AppBundle\Fixtures\Company
 */
class InsuranceRepresentative extends Fixture implements DependentFixtureInterface
{
  /**
   * @return array
   */
  function getDependencies()
  {
    return [
      CompanyBranch::class,
    ];
  }

  /**
   * @param ObjectManager $manager
   */
  public function load(ObjectManager $manager)
  {
    $representative = new \AppBundle\Entity\Company\InsuranceRepresentative();
    $representative->setBranch($this->getReference('sogaz-med-66'));
    $representative->setEmail('test@test.com');
    $manager->persist($representative);
    $manager->flush();
  }
}