<?php

namespace AppBundle\Admin\InsuranceCompany;

use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Form\DataTransformer\RegionToEntityTransformer;
use AppBundle\Repository\Geo\RegionRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sonata\Form\Type\CollectionType;

/**
 * Class InsuranceRepresentativeAdmin
 * @package AppBundle\Admin\InsuranceCompany
 */
class InsuranceRepresentativeAdmin extends AbstractAdmin
{
  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('email')
    ;
  }
}