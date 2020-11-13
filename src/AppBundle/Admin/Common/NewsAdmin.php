<?php


namespace AppBundle\Admin\Common;

use NewsBundle\Admin\NewsAdmin as Base;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class NewsAdmin extends Base
{
  public function configureFormFields(FormMapper $form)
  {
    parent::configureFormFields($form);
    $form->remove('text');
    $form->remove('isExternal');
    $form->get('externalUrl')->setRequired(true);

    $form->getFormBuilder()->addEventListener(FormEvents::PRE_SUBMIT,
      function (FormEvent $event)
      {

        if (!$event->getData()['externalUrl'])
        {
          $event->getForm()->addError(new FormError('Введите внешний URL!'));
        }
      });

  }
}