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

namespace AppBundle\Synchronization\Handler;

use Accurateweb\SynchronizationBundle\Model\Handler\ArgsAwareInterface;
use Accurateweb\SynchronizationBundle\Model\Handler\TransferHandler;
use Symfony\Component\Console\Exception\InvalidOptionException;

/**
 * Class OrganizationTransferHandler
 * @package AppBundle\Synchronization\Handler
 */
class OrganizationTransferHandler extends TransferHandler implements ArgsAwareInterface
{
  /**
   * @var
   */
  private $year;

  protected function preTransfer()
  {
    $this->query('INSERT IGNORE INTO s_regions (code, name) (SELECT region_code, region FROM s_medical_organizations_tmp GROUP BY region_code, region)');
    $sql = 'UPDATE s_medical_organizations_tmp so_tmp
            LEFT JOIN s_regions sr ON sr.code = so_tmp.region_code
            SET so_tmp.region_id = sr.ID
    ';
    $this->query($sql);
  }

  protected function doTransfer()
  {
    parent::doTransfer();
  }

  protected function postTransfer()
  {
    $sql = 'INSERT INTO s_organization_chief_medical_officers 
            (organization_id, first_name, last_name, middle_name) 
            SELECT so_tmp.code, so_tmp.firstName, so_tmp.lastName, so_tmp.middleName
            FROM s_medical_organizations_tmp so_tmp
            ON DUPLICATE KEY UPDATE first_name=so_tmp.firstName, last_name=so_tmp.lastName, middle_name=so_tmp.middleName';
    $this->query($sql);

    $sql = 'INSERT INTO s_organization_years 
            (organization_code, year) 
            SELECT so_tmp.code, ' . $this->year . '
            FROM s_medical_organizations_tmp so_tmp
            ON DUPLICATE KEY UPDATE organization_code=so_tmp.code, year=' . $this->year;
    $this->query($sql);
  }

  public function setCmdOptions($options)
  {
    $this->year = $options['year'];
  }
}