<?php


namespace Tests\AppBundle\Fixtures\Question;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Question extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $number = new \AppBundle\Entity\Question\Question();
    $number->setQuestion('Пример вопроса');
    $number->setAnswer('Пример ответа');
    $manager->persist($number);
    $manager->flush();

    $this->addReference('question-simple', $number);
  }
}