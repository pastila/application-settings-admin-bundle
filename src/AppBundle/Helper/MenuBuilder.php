<?php


namespace AppBundle\Helper;


use AppBundle\Entity\Menu\AbstractMenu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MenuBuilder
 * @package AppBundle\Helper
 */
class MenuBuilder
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * @var RouterInterface
   */
  private $router;

  /**
   * MenuBuilder constructor.
   * @param RouterInterface $router
   * @param EntityManagerInterface $entityManager
   */
  public function __construct(
    RouterInterface $router,
    EntityManagerInterface $entityManager
  )
  {
    $this->router = $router;
    $this->entityManager = $entityManager;
  }

  /**
   * @param $menu
   * @param $class
   * @param null $attributes
   * @return mixed
   */
  public function getMenu($menu, $class, $attributes = null)
  {
    $baseUrl = $this->router->getContext()->getHost();
    $menuItems = $this->entityManager
      ->getRepository($class)
      ->getQueryAllByPosition()
      ->getQuery()
      ->getResult();

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