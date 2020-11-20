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
    $baseUrl = $this->container->get('router')->getContext()->getHost();
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');
    $menuHeader = $em->getRepository(MenuHeader::class)
      ->getAll()
      ->getQuery()
      ->getResult();

    return  $this->getMenu($menu, $menuHeader, $baseUrl, null);
  }

  public function headerMenuMobile(FactoryInterface $factory, array $options): ItemInterface
  {
    $baseUrl = $this->container->get('router')->getContext()->getHost();
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');
    $menuHeader = $em->getRepository(MenuHeader::class)
      ->getAll()
      ->getQuery()
      ->getResult();
    $menu->setChildrenAttribute('class', 'nav-mobile__list');

    return  $this->getMenu($menu, $menuHeader, $baseUrl, ['class' => 'nav-mobile__list-item']);
  }

  public function footerMenu(FactoryInterface $factory, array $options): ItemInterface
  {
    $baseUrl = $this->container->get('router')->getContext()->getHost();
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');
    $menuFooter = $em->getRepository(MenuFooter::class)
      ->getAll()
      ->getQuery()
      ->getResult();

    return $this->getMenu($menu, $menuFooter, $baseUrl, null);
  }

  /**
   * @param $menu
   * @param $menuItems
   * @param $baseUrl
   * @param null $attributes
   * @return mixed
   */
  private function getMenu($menu, $menuItems, $baseUrl, $attributes = null)
  {
    foreach ($menuItems as $item)
    {
      /**
       * @var MenuFooter $item
       */
      $iAnchor = false;
      $url = $item->getUrl();
      if (stripos($item->getUrl(), $baseUrl) !== false)
      {
        $iAnchor = stripos($item->getUrl(), '#');
        $url = ($iAnchor !== false) ? (mb_substr($url, $iAnchor)) : $url;
      }
      $option = ['uri' => $url];
      if ($attributes !== null)
      {
        $option['attributes'] = $attributes;
      }
      $menu->addChild($item->getText(), $option)->setAttribute('isAnchor', $iAnchor !== false);
    }

    return $menu;
  }
}