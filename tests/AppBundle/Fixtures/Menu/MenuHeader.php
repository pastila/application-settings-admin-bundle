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
    $header = new \AppBundle\Entity\Menu\MenuHeader();
    $header->setText('Header');
    $header->setUrl('http://bezbahil.ru/');
    $manager->persist($header);
    $manager->flush();

    $this->addReference('menu_header-simple', $header);
  }
}