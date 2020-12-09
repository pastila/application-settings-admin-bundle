<?php


namespace Tests\AppBundle\Fixtures\Company;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\User\User;

class Comment extends Fixture implements DependentFixtureInterface
{
  /**
   * @return array
   */
  function getDependencies()
  {
    return [
      Feedback::class,
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
     * Комментарий администратора
     */
    $comment1 = new \AppBundle\Entity\Company\Comment();
    $comment1
      ->setFeedback($this->getReference('feedback-simple'))
      ->setText('Комментарий администратора')
      ->setUser($this->getReference('user-admin'));
    $manager->persist($comment1);

    /**
     * Комментарий представителя страховой службы
     */
    $comment2 = new \AppBundle\Entity\Company\Comment();
    $comment2
      ->setFeedback($this->getReference('feedback-simple'))
      ->setText('Комментарий представителя страховой службы')
      ->setUser($this->getReference('user-sogaz-med-66'));
    $manager->persist($comment2);

    /**
     * Комментарий пользователя
     */
    $comment3 = new \AppBundle\Entity\Company\Comment();
    $comment3
      ->setFeedback($this->getReference('feedback-simple'))
      ->setText('Комментарий пользователя')
      ->setUser($this->getReference('user-simple'));
    $manager->persist($comment3);

    $manager->flush();
    $this->addReference('comment-admin', $comment1);
    $this->addReference('comment-representative', $comment2);
    $this->addReference('comment-simple', $comment3);
  }
}