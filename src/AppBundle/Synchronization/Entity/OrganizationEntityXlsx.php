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

namespace AppBundle\Synchronization\Entity;

use Accurateweb\SynchronizationBundle\Model\Entity\Base\BaseEntity;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationEntityXlsx extends BaseEntity
{
  public function parse ($source, $parent = null)
  {
    $sourceResolver = new OptionsResolver();
    $this->configureResolver($sourceResolver);
    $data = $sourceResolver->resolve($source);
    $code = preg_replace('/^(\d+)\s.*/', '$1', $data['region']);
    $data['region_code'] = $code;
    $this->setValues($data);
  }

  protected function configureResolver(OptionsResolver $optionsResolver)
  {
    $optionsResolver->setRequired([
      'region',
      'code',
      'fullName',
      'name',
      'address',
      'lastName',
      'firstName',
      'middleName',
    ]);
    $optionsResolver->setDefaults([
    ]);
  }
}