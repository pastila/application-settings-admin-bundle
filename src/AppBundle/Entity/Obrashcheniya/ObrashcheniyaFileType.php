<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Entity\Obrashcheniya;


abstract class ObrashcheniyaFileType
{
  const REPORT = 'report';
  const ATTACH = 'attach';

  public static function getAvailableTypes()
  {
    return [
      self::REPORT,
      self::ATTACH,
    ];
  }

  public static function getAvailableNames()
  {
    return [
      self::REPORT => 'Сгенерированный pdf с обращением',
      self::ATTACH => 'Прикрепленные файлы',
    ];
  }

  public static function getName($key)
  {
    $types = self::getAvailableNames();

    return key_exists($key, $types) ? $types[$key] : $key;
  }
}
