<?php


namespace Tests\AppBundle\Fixtures\Number;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Number extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $numberOne = new \AppBundle\Entity\Number\Number();
    $numberOne->setPosition(1);
    $numberOne->setTitle('999');
    $numberOne->setDescription('Description');
    $numberOne->setUrl('http://bezbahil.ru/');
    $numberOne->setUrlText('Go');
    $manager->persist($numberOne);
    $manager->flush();

    $numberTwo = new \AppBundle\Entity\Number\Number();
    $numberTwo->setPosition(2);
    $numberTwo->setTitle('100 500');
    $numberTwo->setDescription('Example');
    $numberTwo->setUrl('http://example.ru/');
    $numberTwo->setUrlText('Go');
    $manager->persist($numberTwo);
    $manager->flush();

    $this->addReference('number-simple-one', $numberOne);
    $this->addReference('number-simple-two', $numberTwo);
  }
}