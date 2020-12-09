<?php


namespace Tests\AppBundle\Fixtures\Company;


use AppBundle\Entity\Company\FeedbackModerationStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Tests\AppBundle\Fixtures\User\User;

class Feedback extends Fixture implements DependentFixtureInterface
{
  /**
   * @return array
   */
  function getDependencies()
  {
    return [
      CompanyBranch::class,
      User::class,
    ];
  }

  /**
   * @param ObjectManager $manager
   * @throws \Exception
   */
  public function load(ObjectManager $manager)
  {
    $feedback = new \AppBundle\Entity\Company\Feedback();
    $feedback
      ->setBranch($this->getReference('sogaz-med-66'))
      ->setAuthorName('From fixtures')
      ->setText('Foo')
      ->setValuation(4)
      ->setCreatedAt(new \DateTime('2020-01-01'))
      ->setUpdatedAt(new \DateTime('2020-01-01'))
      ->setModerationStatus(FeedbackModerationStatus::MODERATION_ACCEPTED);
    $manager->persist($feedback);

    $feedbackArmstrong = new \AppBundle\Entity\Company\Feedback();
    $feedbackArmstrong
      ->setBranch($this->getReference('sogaz-med-66'))
      ->setAuthorName('Армстронг')
      ->setText('Привет Мир!')
      ->setValuation(4)
      ->setCreatedAt(new \DateTime('2020-01-02'))
      ->setUpdatedAt(new \DateTime('2020-01-02'))
      ->setModerationStatus(FeedbackModerationStatus::MODERATION_ACCEPTED);
    $manager->persist($feedbackArmstrong);

    /**
     * Не прошедший модерацию отзыв от пользователя
     */
    $feedback4 = new \AppBundle\Entity\Company\Feedback();
    $feedback4
      ->setBranch($this->getReference('sogaz-med-66'))
      ->setAuthor($this->getReference('user-simple'))
      ->setTitle('Не прошедший модерацию отзыв от пользователя')
      ->setText('текст комментария 3')
      ->setValuation(3)
      ->setModerationStatus(FeedbackModerationStatus::MODERATION_NONE);
    $manager->persist($feedback4);

    /**
     * Отклоненный отзыв от пользователя
     */
    $feedback5 = new \AppBundle\Entity\Company\Feedback();
    $feedback5
      ->setBranch($this->getReference('sogaz-med-66'))
      ->setAuthor($this->getReference('user-simple'))
      ->setTitle('Отклоненный отзыв от пользователя')
      ->setText('текст комментария 5')
      ->setValuation(4)
      ->setModerationStatus(FeedbackModerationStatus::MODERATION_REJECTED);
    $manager->persist($feedback5);

    /**
     * Отзыв от пользователя с неактивным филиалом
     */
    $feedback6 = new \AppBundle\Entity\Company\Feedback();
    $feedback6
      ->setBranch($this->getReference('arsenal-66'))
      ->setAuthor($this->getReference('user-simple'))
      ->setTitle('Отзыв от пользователя')
      ->setText('текст комментария 6')
      ->setValuation(3)
      ->setModerationStatus(FeedbackModerationStatus::MODERATION_ACCEPTED);
    $manager->persist($feedback6);

    /**
     * Отзыв от пользователя с неактивной компанией
     */
    $feedback7 = new \AppBundle\Entity\Company\Feedback();
    $feedback7
      ->setBranch($this->getReference('akbars-66'))
      ->setAuthor($this->getReference('user-simple'))
      ->setTitle('Отзыв от пользователя')
      ->setText('текст комментария 7')
      ->setValuation(3)
      ->setModerationStatus(FeedbackModerationStatus::MODERATION_ACCEPTED);
    $manager->persist($feedback7);

    $manager->flush();

    $this->addReference('feedback-simple', $feedback);
    $this->addReference('feedback-armstrong', $feedbackArmstrong);
    $this->addReference('feedback-moderation-not', $feedback4);
    $this->addReference('feedback-moderation-rejected', $feedback5);
    $this->addReference('feedback-branch-not', $feedback6);
    $this->addReference('feedback-company-not', $feedback7);
  }
}