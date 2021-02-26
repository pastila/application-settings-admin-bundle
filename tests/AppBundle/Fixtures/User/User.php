<?php


namespace Tests\AppBundle\Fixtures\User;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\Company\CompanyBranch;

class User extends Fixture implements DependentFixtureInterface
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
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $admin = new \AppBundle\Entity\User\User();
    $admin->setLogin('Admin');
    $admin->setEmail('admin@admin.com');
    $admin->setRepresentative(false);
    $admin->setFirstName('Константин');
    $admin->setMiddleName('Эдуардович');
    $admin->setLastName('Циолковский');
    $admin->setTermsAndConditionsAccepted(true);
    $admin->setPassword("");
    $admin->setRoles([\AppBundle\Entity\User\User::ROLE_ADMIN]);
    $manager->persist($admin);

    $user1 = new \AppBundle\Entity\User\User();
    $user1->setLogin('korolev');
    $user1->setEmail('korolev@mail.com');
    $user1->setRepresentative(true);
    $user1->setBranch($this->getReference('sogaz-med-66'));
    $user1->setFirstName('Сергей');
    $user1->setMiddleName('Павлович');
    $user1->setLastName('Королев');
    $user1->setTermsAndConditionsAccepted(true);
    $user1->setPassword("");
    $manager->persist($user1);

    $user2 = new \AppBundle\Entity\User\User();
    $user2->setLogin('glushko');
    $user2->setEmail('glushko@mail.com');
    $user2->setRepresentative(false);
    $user2->setFirstName('Валентин');
    $user2->setMiddleName('Петрович');
    $user2->setLastName('Глушко');
    $user2->setTermsAndConditionsAccepted(true);
    $user2->setPassword("");
    $manager->persist($user2);

    $manager->flush();
    $this->addReference('user-admin', $admin);
    $this->addReference('user-sogaz-med-66', $user1);
    $this->addReference('user-simple', $user2);
  }
}