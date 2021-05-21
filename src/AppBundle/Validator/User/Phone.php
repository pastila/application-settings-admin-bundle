<?php

namespace AppBundle\Validator\User;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Phone extends Constraint
{
  public $message = 'Номер телефона содержит ошибки';
}