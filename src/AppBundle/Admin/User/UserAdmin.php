<?php

namespace AppBundle\Admin\User;

use AppBundle\Entity\User\User;
use AppBundle\Form\Admin\Common\MaskedType;
use AppBundle\Form\Admin\Company\InsuranceCompanyBranchAutocompleteType;
use AppBundle\Repository\User\PatientRepository;
use AppBundle\Validator\User\Phone;
use FOS\UserBundle\Model\UserManager;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserAdmin extends AbstractAdmin
{
  protected function configureListFields (ListMapper $list)
  {
    $list
      ->add('email')
      ->add('insurancePolicyNumber')
      ->add('insuranceCompany')
      ->add('fio')
      ->add('_action', null, array(
        'label' => 'Действия',
        'actions' => array(
          'patients' => [
            'template' => '@App/admin/user/patients_btn.htm.twig',
          ],
          'edit' => array(),
          'delete' => array(),
        )
      ));
  }

  protected function configureFormFields (FormMapper $form)
  {
    $user = $this->getSubject();
    $form
      ->tab('Данные пользователя')
      ->with('Основное')
      ->add('email', TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('plainPassword', PasswordType::class, [
        'required' => false,
        'help' => 'Введите пароль, чтобы изменить его',
        'constraints' => $this->getSubject() && $this->getSubject()->getId() ? [] : [
          new NotBlank(),
        ],
      ])
      ->add('lastName', TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('firstName', TextType::class, [
        'required' => true,
        'constraints' => [
          new NotBlank(),
        ],
      ])
      ->add('middleName', TextType::class, [
        'required' => false,
      ])
      ->add('birthDate', DatePickerType::class, [
        'format' => 'd MMMM yyyy',
        'view_timezone' => 'UTC',
        'model_timezone' => 'UTC',
      ])
      ->add('phone', MaskedType::class, [
        'constraints' => [
          new Phone(),
        ],
        'mask' => '+7(999)999-99-99',
      ])
      ->add('insurancePolicyNumber')
      ->end();

    if ($user && $user->getId())
    {
      $form
        ->with('Пациент')
        ->add('mainPatient', null, [
          'label' => 'Основой пациент',
          'help' => 'Пациент, которым является пользователь',
          'query_builder' => function(PatientRepository $patientRepository) use ($user)
          {
            return $patientRepository
              ->createQueryBuilder('p')
              ->where('p.user = :user')
              ->setParameter('user', $user);
          },
        ])
        ->end();
    }

    $form
      ->with('Представительство')
      ->add('representative', ChoiceFieldMaskType::class, [
        'choices' => [
          'Нет' => '0',
          'Да' => '1',
        ],
        'map' => [
          '1' => [
            'branch',
          ],
        ],
      ])
      ->add('branch', InsuranceCompanyBranchAutocompleteType::class, [
        'label' => false,
      ])
      ->end()
      ->with('Роли')
      ->add('admin', CheckboxType::class, [
        'label' => 'Администратор',
        'required' => false,
      ])
      ->end()
      ->end()
      ;
  }

  protected function configureDatagridFilters (DatagridMapper $filter)
  {
    $filter->add('email');
    $filter->add('insurancePolicyNumber');
  }

  /**
   * @param User $object
   */
  public function postPersist ($object)
  {
    parent::postPersist($object);

    if ($object->getPlainPassword())
    {
      $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager')->updatePassword($object);
    }
  }

  /**
   * @param User $object
   */
  public function postUpdate ($object)
  {
    parent::postUpdate($object);

    if ($object->getPlainPassword())
    {
      $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager')->updatePassword($object);
    }
  }

  protected function configureTabMenu (MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
  {
    parent::configureTabMenu($menu, $action, $childAdmin);

    if ($childAdmin === null)
    {
      if ($action === 'edit')
      {
        $menu->addChild('Пациенты', [
            'uri' => $this->getChild('main.admin.patient')->generateUrl('list'),
        ]);
      }
    }
  }
}