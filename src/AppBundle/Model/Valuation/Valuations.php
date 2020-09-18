<?php


namespace AppBundle\Model\Checkout;


final class Valuations
{
  const VALUATION_1 = '1';
  const VALUATION_2 = '2';
  const VALUATION_3 = '3';
  const VALUATION_4 = '4';
  const VALUATION_5 = '5';

  public static function getAvailableValuations ()
  {
    return [
      self::VALUATION_1,
      self::VALUATION_2,
      self::VALUATION_3,
      self::VALUATION_4,
      self::VALUATION_5,
    ];
  }
}