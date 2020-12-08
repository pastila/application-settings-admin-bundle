<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
  die();
}

/**
 * Формирование URL для скачивания файла обращения в symfony
 * @param $path
 * @param $name
 * @return string
 */
function parsingPdfPath($name)
{
  $pos = strpos($name, 'PDF_');
  $str = mb_strcut($name, $pos);
  $str = substr_replace($str, ':', 17, 1);
  $str = substr_replace($str, ':', 20, 1);
  return $str;
}

/**
 * @param $array
 * @param $j
 * @return bool
 */
function findExistFile($array, $j)
{
  foreach ($array as $item)
  {
    if ($j == $item['image_number'])
    {
      return true;
    }
  }
  return false;
}