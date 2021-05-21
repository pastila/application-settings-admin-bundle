<?php

namespace AppBundle\Util;

class FileSizeFormatter
{
  /**
   * @param integer $bytes
   * @param array $sizes
   * @return string
   */
  public static function formatSize ($bytes, $sizes = ['bytes', 'kb', 'mb', 'gb', 'tb'])
  {
    if (!is_numeric($bytes))
    {
      return $bytes;
    }

    $needSign = $bytes < 0;
    $bytes = abs($bytes);

    if ($bytes == 0)
    {
      return sprintf('0 %s', $sizes[0]);
    }

    $i = (int)floor(log($bytes) / log(1024));

    if ($i >= count($sizes))
    {
      $i = count($sizes) - 1;
    }

    return round($bytes / pow(1024, $i), 2) * ($needSign?-1:1) . ' ' . $sizes[$i];
  }
}