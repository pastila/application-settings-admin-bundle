<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Entity\Company;


abstract class FeedbackValuation
{
  const ONE = 1;
  const TWO = 2;
  const THREE = 3;
  const FOUR = 4;
  const FIVE = 5;

  /**
   * @return array
   */
  public static function getAvailableNames()
  {
    return [
      self::ONE => '1',
      self::TWO => '2',
      self::THREE => '3',
      self::FOUR => '4',
      self::FIVE => '5',
    ];
  }

  public static function getAvailableValues()
  {
    return array_flip(self::getAvailableNames());
  }
}
