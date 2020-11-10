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
    $number = new \AppBundle\Entity\Number\Number();
    $number->setTitle('Title');
    $number->setDescription('Description');
    $number->setUrl('http://bezbahil.ru/');
    $number->setUrlText('Go');
    $manager->persist($number);
    $manager->flush();

    $this->addReference('number-simple', $number);
  }
}