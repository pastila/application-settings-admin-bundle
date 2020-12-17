<?php

namespace AppBundle\Admin\Geo;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class RegionAdmin
 * @package AppBundle\Admin\Geo
 */
class RegionAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields (ListMapper $list)
  {
    $list
      ->add('name')
      ->add('nameGenitive')
      ->add('code')
      ->add('_action', null, array(
        'label' => 'Действия',
        'actions' => array(
          'edit' => array(),
        )
      ));
  }
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('name', TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 512,
          ]),
        ],
      ])
      ->add('nameGenitive', TextareaType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 512,
          ]),
        ],
      ])
      ->add('code', TextareaType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'max' => 3,
          ]),
        ],
      ]);
  }

  /**
   * @param RouteCollection $collection
   */
  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->remove('create');
  }
}