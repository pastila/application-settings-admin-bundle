<?php

namespace AppBundle\Entity\Menu;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="s_menu_header")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Menu\MenuHeaderRepository")
 */
class MenuHeader extends AbstractMenu
{
}
