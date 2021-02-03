<?php

namespace AppBundle\Admin\InsuranceCompany;

use AppBundle\Form\DataTransformer\RegionToEntityTransformer;
use Doctrine\ORM\PersistentCollection;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\Form\Type\CollectionType;
use AppBundle\Entity\Company\InsuranceRepresentative;

/**
 * Class InsuranceCompanyBranchAdmin
 * @package AppBundle\Admin\InsuranceCompany
 */
class InsuranceCompanyBranchAdmin extends AbstractAdmin
{
  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $subject = $this->getSubject();

    $form
      ->add('region', TextType::class, [
        'label' => 'Регион',
        'attr' => array(
          'readonly' => true,
        ),
      ])
      ->add('representatives', CollectionType::class, [
        'by_reference' => false,
        'label' => 'Представители СМО:',
        'btn_add' => 'Добавить',
        'type_options' => [
          'delete' => true,
        ]
      ], [
        'edit' => 'inline',
        'inline' => 'table',
        'allow_add' => true,
        'allow_delete' => true,
        'sortable' => 'region',
        'admin_code' => 'main.admin.insurance_representative'
      ])
      ->add('published');

    $form ->get('representatives')
      ->addModelTransformer(new CallbackTransformer(
        function ($collection) {
          return $collection;
        },
        function ($collection) use ($subject) {
          /**
           * @var PersistentCollection $collection
           */
          $collection->map(function($value) use ($subject){
            /**
             * @var InsuranceRepresentative $value
             */
            $value->setBranch($subject);
            return $value;
          });
          return $collection;
        }
      ));

    $form->get('region')
      ->addModelTransformer(new RegionToEntityTransformer($this->getSubject()));
  }
}