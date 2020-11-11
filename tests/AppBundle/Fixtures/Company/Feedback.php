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
   * @return mixed
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
      ->setModerationStatus(FeedbackModerationStatus::MODERATION_ACCEPTED)
    ;

    $manager->persist($feedback);

    $manager->flush();
  }

}