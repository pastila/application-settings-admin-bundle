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
    $question1 = new \AppBundle\Entity\Question\Question();
    $question1->setQuestion('Пример вопроса1');
    $question1->setAnswer('Пример ответа1');
    $manager->persist($question1);

    $question2 = new \AppBundle\Entity\Question\Question();
    $question2->setQuestion('Пример вопроса2');
    $question2->setAnswer('Пример ответа2');
    $manager->persist($question2);

    $question3 = new \AppBundle\Entity\Question\Question();
    $question3->setQuestion('Пример вопроса3');
    $question3->setAnswer('Пример ответа3');
    $manager->persist($question3);

    $question4 = new \AppBundle\Entity\Question\Question();
    $question4->setQuestion('Пример вопроса4');
    $question4->setAnswer('Пример ответа4');
    $manager->persist($question4);

    $manager->flush();
  }
}