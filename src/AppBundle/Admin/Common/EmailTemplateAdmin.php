<?php

namespace AppBundle\Admin\Common;


use Sonata\AdminBundle\Form\FormMapper;
use Accurateweb\EmailTemplateBundle\Admin\EmailTemplateAdmin as BaseEmailTemplateAdmin;

class EmailTemplateAdmin extends BaseEmailTemplateAdmin
{
  protected $translationDomain = 'EmailTemplateBundle';

  /**
   * @param FormMapper $form
   */
  protected function configureFormFields(FormMapper $form)
  {
    $form
      ->add('SupportedVariables', 'Accurateweb\\EmailTemplateBundle\\Form\\Type\\SupportedVariablesType', array('label' => 'Доступные переменные'))
      ->add('Subject', 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType', array('label' => 'Шаблон темы письма'))
      ->add('Body', \AppBundle\Form\Common\TinyMceType::class, [
          'attr' => [
            'class' => 'tinymce',
            'tinymce' => '{"theme":"simple"}',
            'data-theme' => 'bbcode',
          ],
          'required' => true,
          'label' => 'Тело письма',
        ]
      )
    ;
  }
}