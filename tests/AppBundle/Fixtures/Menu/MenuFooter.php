<?php


namespace Tests\AppBundle\Fixtures\Menu;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenuFooter extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $footer1 = new \AppBundle\Entity\Menu\MenuFooter();
    $footer1->setPosition(1);
    $footer1->setText('меню_футер_1');
    $footer1->setUrl('http://bezbahil.ru/1');
    $manager->persist($footer1);
    $footer2 = new \AppBundle\Entity\Menu\MenuFooter();
    $footer2->setPosition(2);
    $footer2->setText('меню_футер_2');
    $footer2->setUrl('http://bezbahil.ru/2');
    $manager->persist($footer2);

    $manager->flush();
    $this->addReference('menu_footer-simple_1', $footer1);
    $this->addReference('menu_footer-simple_2', $footer1);
  }
}