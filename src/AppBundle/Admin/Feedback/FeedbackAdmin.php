<?php

namespace AppBundle\Admin\Feedback;

use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Repository\Company\InsuranceCompanyRepository;
use AppBundle\Repository\Geo\RegionRepository;
use AppBundle\Validator\Feedback\FeedbackAuthor;
use AppBundle\Form\Widget\BezbahilAutocompleteCompanyType;
use AppBundle\Model\Esim\EsimStatus;
use AppBundle\Repository\Company\InsuranceCompanyBranchRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class FeedbackAdmin
 * @package AppBundle\Admin\Feedback
 */
class FeedbackAdmin extends AbstractAdmin
{
  /**
   * @var array
   */
  protected $datagridValues = array(
    '_page' => 1,
    '_sort_order' => 'ASC',
    '_sort_by' => 'createdAt',
  );

  /**
   * @param ListMapper $list
   */
  protected function configureListFields(ListMapper $list)
  {
    $list
      ->add('branch', null, [
        'label' => 'СМО',
      ])
      ->add('branch.region', null, [
        'label' => 'Регион',
      ])
      ->add('title', null, [
        'label' => 'Заголовок',
      ])
      ->add('author', null, [
        'label' => 'Пользователь',
      ])
      ->add('createdAt', 'date', [
        'label' => 'Дата создания',
        'format' => 'Y-m-d H:i:s'
      ])
      ->add('updatedAt', 'date', [
        'label' => 'Дата изменения',
        'format' => 'Y-m-d H:i:s'
      ])
      ->add('moderationStatus', 'choice', [
        'label' => 'Статус модерации',
        'editable' => true,
        'choices' => FeedbackModerationStatus::getAvailableNames(),
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
      ->add('company', EntityType::class, [
        'label' => 'Компания',
        'class' => InsuranceCompany::class,
        'choice_label' => 'name',
        'placeholder' => '',
        "expanded" => false,
        'query_builder' => function (InsuranceCompanyRepository $repository)
        {
          return $repository
            ->getWithBranchActive()
            ->orderBy('c.name');
        },
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('region', EntityType::class, [
        'label' => 'Регион',
        'class' => Region::class,
        'choice_label' => 'name',
        'placeholder' => '',
        "expanded" => false,
        'query_builder' => function (RegionRepository $repository)
        {
          return $repository
            ->createQueryBuilder('r')
            ->orderBy('r.name');
        },
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('author', null, [
        'label' => 'Авторизованный пользователь',
        'help' => 'Можно оставить пустым, если пользватель не авторизованный',
      ])
      ->add('authorName', null, [
        'required' => true,
        'label' => 'Имя пользователя',
        'help' => 'Подставляется автоматически после сохранения, если пользователь выбран как авторизованный',
      ])
      ->add("title", TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 3,
            'max' => 255,
          ]),
        ],
      ])
      ->add("text", TextareaType::class, [
        'required' => true,
        'attr' => [
          'rows' => 5
        ],
        'constraints' => [
          new NotBlank(),
          new Length([
            'min' => 10,
          ]),
        ],
      ])
      ->add("createdAt", DateTimePickerType::class, [
        'label' => 'Дата создания',
        'required' => true,
        'by_reference' => true,
        'format' => 'd MMMM yyyy',
        'view_timezone' => 'UTC',
        'model_timezone' => 'UTC',
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add("updatedAt", DateTimePickerType::class, [
        'required' => false,
        'label' => 'Дата изменения',
        'by_reference' => true,
        'format' => 'd MMMM yyyy',
        'view_timezone' => 'UTC',
        'model_timezone' => 'UTC',
        'help' => 'При обновлении изменяется автоматически',
      ])
      ->add('moderationStatus', 'AppBundle\Form\Feedback\FeedbackStatusChoiceType', [
        'required' => true,
        'label' => 'Статус модерации',
        'constraints' => [
          new NotBlank(),
        ],
      ]);
  }

  /**
   * @param DatagridMapper $filter
   */
  protected function configureDatagridFilters(DatagridMapper $filter)
  {
    $filter
      ->add('title')
      ->add('author')
      ->add('author.email')
      ->add('branch.region')
      ->add('branch.company')
      ->add('moderationStatus', null, [], 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
        'choices' => array_flip(FeedbackModerationStatus::getAvailableNames()),
      ]);
  }

  /**
   * @param Feedback $feedback
   */
  public function prePersist($feedback)
  {
    $this->setBranch($feedback);
    $this->setUpdatedAt($feedback);
  }

  /**
   * @param Feedback $feedback
   */
  public function preUpdate($feedback)
  {
    $this->setBranch($feedback);
    $this->setUpdatedAt($feedback);
  }

  /**
   * @param Feedback $feedback
   */
  protected function setBranch($feedback)
  {
    $container = $this->getConfigurationPool()->getContainer();

    if (!empty($feedback->getRegionRaw()) && !empty($feedback->getCompanyRaw()))
    {
      $em = $container->get('doctrine.orm.entity_manager');
      $branch = $em->getRepository(CompanyBranch::class)
        ->findOneBy([
          'region' => $feedback->getRegionRaw(),
          'company' => $feedback->getCompanyRaw(),
        ]);
      $feedback->setBranch($branch);
    }
  }

  /**
   * @param Feedback $feedback
   */
  protected function setUpdatedAt($feedback)
  {
    $feedback->setUpdatedAt(empty($feedback->getUpdatedAt()) ? $feedback->getCreatedAt() : $feedback->getUpdatedAt());
  }

  /**
   *
   */
  protected function attachInlineValidator()
  {
    $metadata = $this->validator->getMetadataFor($this->getClass());
    $metadata->addConstraint(new FeedbackAuthor());

    return parent::attachInlineValidator();
  }

  /**
   * @param RouteCollection $collection
   */
  protected function configureRoutes(RouteCollection $collection)
  {
    $collection->add('reloadRegions', 'reload-regions');
  }

  /**
   * @param string $name
   * @return string|null
   */
  public function getTemplate($name)
  {
    switch ($name)
    {
      case 'edit':
        return 'SonataAdminBundle:CRUD:region_edit.html.twig';
        break;
      default:
        return parent::getTemplate($name);
        break;
    }
  }
}