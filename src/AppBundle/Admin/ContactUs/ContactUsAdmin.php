<?php

namespace AppBundle\Admin\ContactUs;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class ContactUsAdmin
 * @package AppBundle\Admin\ContactUs
 */
class ContactUsAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields (ListMapper $list)
  {
    $list
      ->add('authorName', null, [
        'label' => 'Автор',
      ])
      ->add('email', null, [
        'label' => 'Почта',
      ])
      ->add('message', null, [
        'label' => 'Сообщение',
      ])
      ->add('_action', null, array(
        'label' => 'Действия',
        'actions' => array(
          'delete' => array(),
        )
      ));
  }

  /**
   * @param RouteCollection $collection
   */
  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->remove('create');
  }
}