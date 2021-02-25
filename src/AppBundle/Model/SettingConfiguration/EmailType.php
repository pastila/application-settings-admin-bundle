<?php

namespace AppBundle\Model\SettingConfiguration;

use Accurateweb\ApplicationSettingsAdminBundle\Model\SettingConfiguration\StringSettingConfiguration;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class EmailType
 * @package AppBundle\Form\Common
 */
class EmailType extends StringSettingConfiguration
{
  public function getFormOptions($value)
  {
    $params = [
      'constraints' => [
        new NotBlank(),
        new Email(),
        new Length([
          'min' => 3,
          'max' => 255,
        ]),
      ]
    ];

    return array_merge(parent::getFormOptions($value), $params);
  }

  public function getFormType()
  {
    return 'Symfony\Component\Form\Extension\Core\Type\EmailType';
  }
}
