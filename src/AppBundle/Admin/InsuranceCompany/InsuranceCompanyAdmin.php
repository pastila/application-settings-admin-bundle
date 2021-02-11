<?php

namespace AppBundle\Admin\InsuranceCompany;


use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Company\InsuranceRepresentative;
use AppBundle\Entity\Geo\Region;
use AppBundle\Validator\InsuranceCompany\InsuranceCompanyBranchPublished;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sonata\Form\Type\CollectionType;

/**
 * Class InsuranceCompanyAdmin
 * @package AppBundle\Admin\Company
 */
class InsuranceCompanyAdmin extends AbstractAdmin
{
  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('kpp')
      ->add('name')
      ->add('published', null, [
        'label' => 'Публикация',
      ])
      ->add('_action', null, array(
        'label' => 'Действия',
        'actions' => array(
          'edit' => array(),
          'delete' => array(),
        )
      ));
  }

  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->tab('СМО')
        ->add('logo', 'Accurateweb\MediaBundle\Form\ImageType', [
          'required' => false,
          'label' => 'Логотип',
        ])
        ->add("name", TextType::class, [
          'required' => true,
          'constraints' => [
            new NotBlank(),
            new Length([
              'min' => 3,
              'max' => 255,
            ]),
          ],
        ])
        ->add("kpp", TextType::class, [
          'required' => true,
          'constraints' => [
            new NotBlank(),
            new Length([
              'min' => 3,
              'max' => 255,
            ]),
          ],
        ])
        ->add('published')
      ->end()
      ->end();

    if ($this->getSubject() && $this->getSubject()->getId())
    {
      $form
        ->tab('Филиалы')
        ->add('branches', CollectionType::class, [
          'by_reference' => false,
          'label' => 'Филиалы:',
          'btn_add' => false,
          'type_options' => [
            'delete' => false,
            'delete_options' => [
              'type_options' => [
                'mapped'   => false,
                'required' => false,
              ]
            ]
          ]
        ], [
          'edit' => 'inline',
          'inline' => 'table',
          'sortable' => 'regions',
          'order' => 'DESC',
          'admin_code' => 'main.admin.insurance_company_branch'
        ])
        ->end()
        ->end();
    }
  }

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('kpp')
      ->add('name')
      ->add('published');
  }

  /**
   *
   */
  protected function attachInlineValidator()
  {
    $metadata = $this->validator->getMetadataFor($this->getClass());
    $metadata->addConstraint(new InsuranceCompanyBranchPublished());

    return parent::attachInlineValidator();
  }

  /**
   * @param $company
   */
  public function prePersist($company)
  {
    $this->addNewBranch($company);
  }

  /**
   * @param $company
   */
  public function preUpdate($company)
  {
    $this->addNewBranch($company);
  }

  /**
   * @param InsuranceCompany $company
   */
  private function addNewBranch($company)
  {
    $container = $this->getConfigurationPool()->getContainer();
    $em = $container->get('doctrine.orm.entity_manager');

    $regions = $em->getRepository(Region::class)
      ->createQueryBuilder('r')
      ->orderBy('r.name', 'ASC')
      ->getQuery()
      ->getResult();
    foreach ($regions as $region) {
      $branch = $em->getRepository(InsuranceCompanyBranch::class)
        ->findOneBy([
          'region' => $region,
          'company' => $company
        ]);

      if (!$branch)
      {
        $branch = new InsuranceCompanyBranch();
        $branch->setRegion($region);
        $branch->setCompany($company);
        $branch->setKpp($company->getKpp());
        $branch->setPublished(false);
        $company->addBranch($branch);
      }
    }
  }
}