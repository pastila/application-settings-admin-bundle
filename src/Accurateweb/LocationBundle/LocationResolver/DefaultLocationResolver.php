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

namespace Accurateweb\LocationBundle\LocationResolver;

use Accurateweb\LocationBundle\Model\UserLocation;

class DefaultLocationResolver implements LocationResolverInterface
{
  private $location;

  public function __construct ($cityName, $cityCode, $countryCode)
  {
    $this->location = new UserLocation();
    $this->location
      ->setCityName($cityName)
      ->setCityCode($cityCode)
      ->setCountryCode($countryCode);
  }

  public function getUserLocation()
  {
    return $this->location;
  }
}