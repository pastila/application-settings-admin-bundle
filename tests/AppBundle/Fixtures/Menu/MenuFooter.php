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
    $footer = new \AppBundle\Entity\Menu\MenuFooter();
    $footer->setText('Footer');
    $footer->setUrl('http://bezbahil.ru/');
    $manager->persist($footer);
    $manager->flush();

    $this->addReference('menu_footer-simple', $footer);
  }
}