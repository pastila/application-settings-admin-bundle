<?php
/**
 *  (c) 2019 ИП Рагозин Денис Николаевич. Все права защищены.
 *
 *  Настоящий файл является частью программного продукта, разработанного ИП Рагозиным Денисом Николаевичем
 *  (ОГРНИП 315668300000095, ИНН 660902635476).
 *
 *  Алгоритм и исходные коды программного кода программного продукта являются коммерческой тайной
 *  ИП Рагозина Денис Николаевича. Любое их использование без согласия ИП Рагозина Денис Николаевича рассматривается,
 *  как нарушение его авторских прав.
 *   Ответственность за нарушение авторских прав наступает в соответствии с действующим законодательством РФ.
 */

/**
 * Created by PhpStorm.
 * User: Dancy
 * Date: 04.10.2017
 * Time: 17:31
 */

namespace Accurateweb\SlugifierBundle\Model;


interface SlugifierInterface
{
  /**
   * Slugifies text
   *
   * @param string $text Text to slugify, i.e. Text article title
   * @param string $separator A char (or string) used as a separator for words. This must be a non-space character.
   * @return string Slugified text, i.e. text-article-title
   */
  public function slugify($text, $separator='-');
}