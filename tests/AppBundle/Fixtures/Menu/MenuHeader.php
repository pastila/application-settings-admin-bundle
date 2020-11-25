<?php


namespace Tests\AppBundle\Fixtures\Menu;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenuHeader extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $header1 = new \AppBundle\Entity\Menu\MenuHeader();
    $header1->setPosition(1);
    $header1->setText('меню_шапка_1');
    $header1->setUrl('http://bezbahil.ru/1');
    $manager->persist($header1);
    $header2 = new \AppBundle\Entity\Menu\MenuHeader();
    $header2->setPosition(2);
    $header2->setText('меню_шапка_2');
    $header2->setUrl('http://bezbahil.ru/2');
    $manager->persist($header2);

    $manager->flush();
    $this->addReference('menu_header-simple_1', $header1);
    $this->addReference('menu_header-simple_2', $header2);
  }
}