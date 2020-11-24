<?php


namespace AppBundle\Model\SettingConfiguration;

use Accurateweb\ApplicationSettingsAdminBundle\Model\SettingConfiguration\EntitySettingConfiguration as BaseEntitySettingConfiguration;

class SelectEntitySettingConfiguration extends BaseEntitySettingConfiguration
{
  public function toString ($value)
  {
    if (is_null($value))
    {
      return '';
    }

    if (method_exists($value, '__toString'))
    {
      return $value->__toString();
    }

    return $this->repository->find($value);
  }

  public function transform ($value)
  {
    if (is_null($value))
    {
      return null;
    }

    return $value;
  }

  public function reverseTransform ($value)
  {
    if (is_null($value))
    {
      return null;
    }

    return $value;
  }
}