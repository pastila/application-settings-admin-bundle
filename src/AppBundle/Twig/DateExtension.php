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

namespace AppBundle\Twig;

class DateExtension extends \Twig_Extension
{

  public function getFilters ()
  {
    return array(
      new \Twig_SimpleFilter('prepareDate', array($this, 'prepareDate')),
      new \Twig_SimpleFilter('formatInterval', array($this, 'formatInterval')),
    );
  }

  /**
   * @param \DateTime|string $content
   * @param string $format
   * @return string
   */
  public function prepareDate ($content, $format = 'j F Y H:i')
  {
    $months = [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
    $shortMonths = [1 => 'янв', 'фев', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'];

    if ($content instanceof \DateTime)
    {
      $date = $content;
    }
    elseif (date_create($content))
    {
      $date = date_create($content);

      if (!$date)
      {
        return $content;
      }
    }
    else
    {
      return $content;
    }

    $month = $date->format('n');
    $format = str_replace('F', $months[$month], $format);
    $format = str_replace('M', $shortMonths[$month], $format);
    $return = $date->format($format);

    return $return;
  }

  /*
   * Форматирует как и \DateInterval::format
   * + дополнительные параметры:
   *  д - количество дней + слово "дней" в нужном падеже
   *  ч - количество часов + слово "часов" в нужном падеже
   *  м - количество минут + слово "минут" в нужном падеже
   */
  public function formatInterval (\DateTime $date, $format = '%д %ч %м', \DateTime $secondDate = null)
  {
    if (!$secondDate)
    {
      $secondDate = new \DateTime('now');
    }

    $diff = $secondDate->diff($date);
    $days = (int)$diff->format('%d');
    $hours = (int)$diff->format('%h');
    $minutes = (int)$diff->format('%i');

    if ($days)
    {
      $dayString = sprintf('%s %s', $days, 'дн.');
      $format = str_replace('%д', $dayString, $format);
    }
    else
    {
      if (preg_match('/%д\s/u', $format))
      {
        $format = preg_replace('/%д\s/u', '', $format);
      }
      elseif (preg_match('/\s%д/u', $format))
      {
        $format = preg_replace('/\s%д/u', '', $format);
      }
      else
      {
        $format = preg_replace('/%д/u', '', $format);
      }
    }

    if ($hours)
    {
      $hourString = sprintf('%s %s', $hours, 'ч.');
      $format = str_replace('%ч', $hourString, $format);
    }
    else
    {
      if (preg_match('/%ч\s/u', $format))
      {
        $format = preg_replace('/%ч\s/u', '', $format);
      }
      elseif (preg_match('/\s%ч/u', $format))
      {
        $format = preg_replace('/\s%ч/u', '', $format);
      }
      else
      {
        $format = preg_replace('/%ч/u', '', $format);
      }
    }

    if ($minutes)
    {
      $minuteString = sprintf('%s %s', $minutes, 'мин.');
      $format = str_replace('%м', $minuteString, $format);
    }
    else
    {
      if (preg_match('/%м\s/u', $format))
      {
        $format = preg_replace('/%м\s/u', '', $format);
      }
      elseif (preg_match('/\s%м/u', $format))
      {
        $format = preg_replace('/\s%м/u', '', $format);
      }
      else
      {
        $format = preg_replace('/%м/u', '', $format);
      }
    }

    return $diff->format($format);
  }

}