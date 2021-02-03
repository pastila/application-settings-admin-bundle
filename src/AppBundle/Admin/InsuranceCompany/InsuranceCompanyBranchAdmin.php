<?php

namespace AppBundle\Admin\InsuranceCompany;

use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Form\DataTransformer\RegionToEntityTransformer;
use AppBundle\Repository\Geo\RegionRepository;
use Doctrine\ORM\PersistentCollection;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
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