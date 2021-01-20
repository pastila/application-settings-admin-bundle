<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Entity\Organization;


abstract class OrganizationStatus
{
  const NOT_ACTIVE = 0;
  const ACTIVE = 1;

  public static function getAvailableValues()
  {
    return [
      self::NOT_ACTIVE,
      self::ACTIVE,
    ];
  }

  public static function getAvailableName()
  {
    return [
      self::NOT_ACTIVE => 'Не активный',
      self::ACTIVE => 'Активный',
    ];
  }

  public static function getName($key)
  {
    $statuses = self::getAvailableName();

    return key_exists($key, $statuses) ? $statuses[$key] : $key;
  }
}
