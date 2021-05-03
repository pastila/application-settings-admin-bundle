<?php

namespace AppBundle\Printer\Excel;

use AppBundle\Entity\Organization\MedicalOrganization;
use AppBundle\Entity\Organization\OrganizationChiefMedicalOfficer;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class OrganizationReportExcelPrinter
 * @package AppBundle\Printer\Excel
 */
class OrganizationReportExcelPrinter
{
  /**
   * Путь до шаблона со списком МО
   */
  const PATH_TO_TEMPLATE_EXCEL = '/src/AppBundle/Resources/excel_templates/mo.xls';

  /**
   * @var string
   */
  protected $excelTemplates;

  /**
   * OrganizationReportExcelPrinter constructor.
   * @param KernelInterface $kernel
   * @param $pathToTemplateExcel
   */
  public function __construct(KernelInterface $kernel)
  {
    $this->excelTemplates = $kernel->getProjectDir() . self::PATH_TO_TEMPLATE_EXCEL;
  }

  /**
   * @param $organizations
   * @param string $output
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
   * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
   */
  public function doPrint($organizations, $output = 'php://output')
  {
    $spreadsheet = $this->readTemplate();
    $sheet = $spreadsheet->getActiveSheet();

    $row = 2;
    foreach ($organizations as $organization)
    {
      /**
       * @var MedicalOrganization $organization
       * @var OrganizationChiefMedicalOfficer $chief
       */
      $chief = $organization->getChiefMedicalOfficer();
      $sheet
        ->setCellValue('A' . $row, $organization->getRegion()->getName())
        ->setCellValue('B' . $row, $organization->getCode())
        ->setCellValue('C' . $row, $organization->getFullName())
        ->setCellValue('D' . $row, $organization->getName())
        ->setCellValue('E' . $row, $organization->getAddress())
        ->setCellValue('F' . $row, $chief ? $chief->getLastName() : '')
        ->setCellValue('G' . $row, $chief ? $chief->getFirstName() : '')
        ->setCellValue('H' . $row, $chief ? $chief->getMiddleName() : '');
      $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($output);
  }

  /**
   * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
   * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
   */
  protected function readTemplate()
  {
    /** @var \PhpOffice\PhpSpreadsheet\Reader\Xlsx $reader */
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->excelTemplates);

    return $reader->load($this->excelTemplates);
  }
}
