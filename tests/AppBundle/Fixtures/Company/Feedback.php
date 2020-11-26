<?php


namespace Tests\AppBundle\Fixtures\Company;


use AppBundle\Entity\Company\FeedbackModerationStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Feedback extends Fixture implements DependentFixtureInterface
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

    $manager->flush();

    $this->addReference('feedback-simple', $feedback);
    $this->addReference('feedback-armstrong', $feedbackArmstrong);
  }
}