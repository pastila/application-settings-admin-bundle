<?php


namespace Tests\AppBundle\Fixtures\Company;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Tests\AppBundle\Fixtures\User\User;
use Doctrine\Persistence\ObjectManager;

class Citation extends Fixture implements DependentFixtureInterface
{
  /**
   * @return array
   */
  function getDependencies()
  {
    return [
      Comment::class,
      User::class,
    ];
  }

  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    /**
     * Цитата предствителя страховой службы
     */
    $citation1 = new \AppBundle\Entity\Company\Citation();
    $citation1
      ->setComment($this->getReference('comment-simple'))
      ->setText('Цитата предствителя страховой службы')
      ->setUser($this->getReference('user-sogaz-med-66'))
      ->setRepresentative(true);
    $manager->persist($citation1);

    /**
     * Цитата администратора
     */
    $citation2 = new \AppBundle\Entity\Company\Citation();
    $citation2
      ->setComment($this->getReference('comment-simple'))
      ->setText('Цитата администратора')
      ->setUser($this->getReference('user-admin'));
    $manager->persist($citation2);

    /**
     * Цитата пользователя
     */
    $citation3 = new \AppBundle\Entity\Company\Citation();
    $citation3
      ->setComment($this->getReference('comment-simple'))
      ->setText('Цитата пользователя')
      ->setUser($this->getReference('user-simple'));
    $manager->persist($citation3);

    $manager->flush();
    $this->addReference('citation-sogaz-med-66', $citation1);
    $this->addReference('citation-admin', $citation2);
    $this->addReference('citation-simple', $citation3);
  }
}