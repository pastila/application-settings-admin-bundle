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
    $menu = $factory->createItem('root');
    $helper = $this->container->get('AppBundle\Helper\MenuBuilder');

    return $helper->getMenu($menu, MenuHeader::class, ['class' => 'nav-mobile__list-item']);
  }

  public function headerMenuMobile(FactoryInterface $factory, array $options): ItemInterface
  {
    $menu = $factory->createItem('root');
    $menu->setChildrenAttribute('class', 'nav-mobile__list');
    $helper = $this->container->get('AppBundle\Helper\MenuBuilder');

    return $helper->getMenu($menu, MenuHeader::class, ['class' => 'nav-mobile__list-item']);
  }

  public function footerMenu(FactoryInterface $factory, array $options): ItemInterface
  {
    $menu = $factory->createItem('root');
    $helper = $this->container->get('AppBundle\Helper\MenuBuilder');

    return $helper->getMenu($menu, MenuFooter::class, null);
  }
}