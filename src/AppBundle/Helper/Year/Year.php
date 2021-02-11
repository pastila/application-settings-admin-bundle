<?php


namespace AppBundle\Helper\Year;


class Year
{
  public static function getChoicesYear()
  {
    $years = [];
    foreach (self::getYears() as $key => $item)
    {
      $years[$key . ' г.'] = $item;
    }

    return $years;
  }

  public static function getYears()
  {
    $years = [];
    for ($i = 2019; $i <= date("Y"); $i++)
    {
      $years[$i] = $i;
    }
    return $years;
  }

  public static function getYear($value)
  {
    $years = self::getYears();

    return array_search($value, $years);
  }
}