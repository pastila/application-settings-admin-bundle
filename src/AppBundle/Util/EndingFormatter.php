<?php
/**
 * (c) 2017 ИП Рагозин Денис Николаевич. Все права защищены.
 *
 * Настоящий файл является частью программного продукта, разработанного ИП Рагозиным Денисом Николаевичем
 * (ОГРНИП 315668300000095, ИНН 660902635476).
 *
 * Алгоритм и исходные коды программного кода программного продукта являются коммерческой тайной
 * ИП Рагозина Денис Николаевича. Любое их использование без согласия ИП Рагозина Денис Николаевича рассматривается,
 * как нарушение его авторских прав.
 *
 * Ответственность за нарушение авторских прав наступает в соответствии с действующим законодательством РФ.
 */

namespace StoreBundle\Util;

/**
 * Описание класса EndingFormatter
 *
 * @author Dancy
 */
class EndingFormatter
{
  /**
   * Форматирует окончание подписи суммы
   * Порядок падежей: И, Р, Р мн.ч (раздел, раздела, разделов)
   *
   * @param int|float $count
   * @param array $variants раздел, раздела, разделов
   * @returns String
   */
  static public function format ($count, $variants)
  {
    if (is_null($count))
    {
      $count = 0;
    }

    if (!is_numeric($count))
    {
      throw new \InvalidArgumentException(sprintf('First argument should be numeric'));
    }

    if (count($variants) !== 3)
    {
      throw new \InvalidArgumentException('Second argument should have 3 variants of string');
    }

    $count = abs($count);

    if ($count != floor($count))
    {
      return $variants[1];
    }

    $md100 = $count % 100;
    $mod = $count % 10;

    if ($mod == 1 && $md100 != 11)
    {
      return $variants[0];
    }

    if ($mod > 1 && $mod < 5 && ($md100 < 10 || $md100 > 20))
    {
      return $variants[1];
    }

    return $variants[2];
  }
}
