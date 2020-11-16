<?php

namespace AppBundle\Entity\Menu;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="s_menu_footer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Menu\MenuFooterRepository")
 */
class MenuFooter extends AbstractMenu
{
}
