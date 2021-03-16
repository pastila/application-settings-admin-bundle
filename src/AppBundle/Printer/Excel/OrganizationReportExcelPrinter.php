<?php

namespace AppBundle\Printer\Excel;

use AppBundle\Entity\Organization\Organization;
use AppBundle\Entity\Organization\OrganizationChiefMedicalOfficer;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class OrganizationReportExcelPrinter
 * @package AppBundle\Printer\Excel
 */
class OrganizationReportExcelPrinter
{
  /**
   * @var string
   */
  protected $excelTemplates;

  /**
   * OrganizationReportExcelPrinter constructor.
   * @param KernelInterface $kernel
   * @param $pathToTemplateExcel
   */
  public function __construct(
    KernelInterface $kernel,
    $pathToTemplateExcelMO
  )
  {
    $this->excelTemplates = $kernel->getProjectDir() . $pathToTemplateExcelMO;
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
       * @var Organization $organization
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