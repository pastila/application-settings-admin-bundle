<?php

namespace AppBundle\Menu;


use AppBundle\Entity\Menu\AbstractMenu;
use AppBundle\Entity\Menu\MenuFooter;
use AppBundle\Entity\Menu\MenuHeader;
use AppBundle\Entity\Menu\MenuSocial;
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
      ->getQueryAllByPosition()
      ->getQuery()
      ->getResult();

    return $this->getMenu($menu, $menuHeader, $baseUrl, null);
  }

  public function headerMenuMobile(FactoryInterface $factory, array $options): ItemInterface
  {
    $baseUrl = $this->container->get('router')->getContext()->getHost();
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');
    $menuHeader = $em->getRepository(MenuHeader::class)
      ->getQueryAllByPosition()
      ->getQuery()
      ->getResult();
    $menu->setChildrenAttribute('class', 'nav-mobile__list');

    return $this->getMenu($menu, $menuHeader, $baseUrl, ['class' => 'nav-mobile__list-item']);
  }

  public function footerMenu(FactoryInterface $factory, array $options): ItemInterface
  {
    $baseUrl = $this->container->get('router')->getContext()->getHost();
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');
    $menuFooter = $em->getRepository(MenuFooter::class)
      ->getQueryAllByPosition()
      ->getQuery()
      ->getResult();

    return $this->getMenu($menu, $menuFooter, $baseUrl, null);
  }

  public function socialMenu(FactoryInterface $factory, array $options): ItemInterface
  {
    $baseUrl = $this->container->get('router')->getContext()->getHost();
    $em = $this->container->get('doctrine')->getManager();
    $menu = $factory->createItem('root');
    $menuFooter = $em->getRepository(MenuSocial::class)
      ->getQueryAllByPosition()
      ->getQuery()
      ->getResult();
    $menu->setChildrenAttribute('class', 'app-footer__soc');
    $menu->setAttributes([
      'isDiv' => true
    ]);
    $menu = $this->getMenu($menu, $menuFooter, $baseUrl, ['isLiHide' => true, 'target' => '_blank', 'image' => true]);

    return $menu;
  }

  /**
   * @param $menu
   * @param $menuItems
   * @param $baseUrl
   * @param null $attributes
   * @param null $childrenAttributes
   * @return mixed
   */
  private function getMenu($menu, $menuItems, $baseUrl, $attributes = null)
  {
    foreach ($menuItems as $item)
    {
      /**
       * @var AbstractMenu $item
       */
      $iAnchor = false;
      $url = $item->getUrl();
      if (stripos($item->getUrl(), $baseUrl) !== false)
      {
        $iAnchor = stripos($item->getUrl(), '#');
        $url = ($iAnchor !== false) ? (mb_substr($url, $iAnchor)) : $url;
      }
      $option = ['uri' => $url];
      $attributes = empty($attributes) ? [] : $attributes;
      $option['attributes'] = array_merge($attributes, ['base' => $item]);
      $menu->addChild($item->getText(), $option)->setAttribute('isAnchor', $iAnchor !== false);
    }

    return $menu;
  }
}