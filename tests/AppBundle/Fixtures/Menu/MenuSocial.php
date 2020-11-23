<?php


namespace Tests\AppBundle\Fixtures\Menu;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenuSocial extends Fixture
{
  /**
   * @param ObjectManager $manager
   * @return mixed
   */
  public function load(ObjectManager $manager)
  {
    $headerVk = new \AppBundle\Entity\Menu\MenuSocial();
    $headerVk->setPosition(1);
    $headerVk->setText('vk');
    $headerVk->setUrl('http://vk.ru');
    $headerVk->setOriginal('image.svg');
    $manager->persist($headerVk);
    $headerInstagram = new \AppBundle\Entity\Menu\MenuSocial();
    $headerInstagram->setPosition(2);
    $headerInstagram->setText('instagram');
    $headerInstagram->setUrl('http://instagram.ru');
    $headerInstagram->setOriginal('image.jpg');
    $manager->persist($headerInstagram);

    $manager->flush();
    $this->addReference('menu_social-vk', $headerVk);
    $this->addReference('menu_social-instagram', $headerInstagram);
  }
}