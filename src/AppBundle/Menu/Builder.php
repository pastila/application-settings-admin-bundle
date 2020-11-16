<?php

namespace AppBundle\Menu;


use AppBundle\Entity\Menu\MenuFooter;
use AppBundle\Entity\Menu\MenuHeader;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class Builder implements ContainerAwareInterface
{
  use ContainerAwareTrait;

  public function headerMenu(FactoryInterface $factory, array $options): ItemInterface
  {
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');

    $menuHeader = $em->getRepository(MenuHeader::class)
      ->getAll()
      ->getQuery()
      ->getResult();

    foreach ($menuHeader as $item)
    {
      /**
       * @var MenuFooter $item
       */
      $menu->addChild($item->getText(), [
        'uri' => $item->getUrl()
      ])->setAttribute('isExternal', $item->getIsExternal());
    }

    return $menu;
  }

  public function headerMenuMobile(FactoryInterface $factory, array $options): ItemInterface
  {
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');
    $menu->setChildrenAttribute('class', 'nav-mobile__list');

    $menuHeader = $em->getRepository(MenuHeader::class)
      ->getAll()
      ->getQuery()
      ->getResult();

    foreach ($menuHeader as $item)
    {
      /**
       * @var MenuFooter $item
       */
      $menu->addChild($item->getText(), [
        'uri' => $item->getUrl(),
        'attributes' => ['class' => 'nav-mobile__list-item'],
      ])->setAttribute('isExternal', $item->getIsExternal());;
    }

    return $menu;
  }

  public function footerMenu(FactoryInterface $factory, array $options): ItemInterface
  {
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');

    $menuFooter = $em->getRepository(MenuFooter::class)
      ->getAll()
      ->getQuery()
      ->getResult();

    foreach ($menuFooter as $item)
    {
      /**
       * @var MenuFooter $item
       */
      $menu->addChild($item->getText(), [
        'uri' => $item->getUrl(),
      ])->setAttribute('isExternal', $item->getIsExternal());
    }

    return $menu;
  }
}