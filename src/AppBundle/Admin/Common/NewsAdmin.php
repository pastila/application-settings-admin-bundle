<?php


namespace AppBundle\Admin\Common;

use Accurateweb\NewsBundle\Admin\NewsAdmin as Base;
use Sonata\AdminBundle\Form\FormMapper;

class NewsAdmin extends Base
{
  public function configureFormFields(FormMapper $form)
  {
    parent::configureFormFields($form);
  }
}