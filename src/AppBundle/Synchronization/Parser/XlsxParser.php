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

namespace AppBundle\Synchronization\Parser;

use Accurateweb\SynchronizationBundle\Exception\ParserException;
use Accurateweb\SynchronizationBundle\Model\Entity\Base\BaseEntity;
use Accurateweb\SynchronizationBundle\Model\Parser\BaseParser;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Column;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/*
 * Парсер xlsx файлов
 * Принимает опции:
 *  skip_rows - сколько строк пропустить
 *  map - массив с ключом - идентификатор колонки, значение - новый ключ. Ex. ['A' => 'title', 'AN' => 'price']
 */
class XlsxParser extends BaseParser
{
  private $skip_rows = 0;
  private $map;
  private $schemaValidator;
  private $childParsers;

  /*
   * Источник entity (row|column)
   */
  private $source;

  public function __construct ($configuration, $subject, $entityFactory, $schema, $options)
  {
    parent::__construct($configuration, $subject, $entityFactory, $schema, $options);
    $this->childParsers = array();
    $this->skip_rows = !empty($options['skip_rows'])?$options['skip_rows']:0;
    $this->map = $options['map'];
    $this->schemaValidator = isset($options['validator']) && is_callable($options['validator'])?$options['validator']:null;
    $this->source = isset($options['source'])?$options['source']:'row';

    if (isset($options["children"]))
    {
      foreach ($options["children"] as $options)
      {
        if (!is_array($options))
        {
          $options = array('subject' => $options);
        }

        $childSubject = $options['subject'];
        unset($options['subject']);
        $childParser = $this->getServiceConfiguration()->getParser($childSubject);

        if ($childParser instanceof XlsxParser)
        {
          $this->addChildParser($childSubject, $childParser, $options);
        }
      }
    }
  }

  /**
   * @param $filename
   * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
   * @throws ParserException
   */
  protected function loadFile ($filename)
  {
    try
    {
      /** @var Xlsx $reader */
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filename);
      $reader->setReadDataOnly(true);
      $data = $reader->load($filename);
    }
    catch (Exception $e)
    {
      throw new ParserException($e->getMessage());
    }

    return $data;
  }

  /**
   * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $source
   */
  public function parse ($source)
  {
    $sheet = $source->getActiveSheet();

    if ($this->schemaValidator)
    {
      $titleRow = $this->getRowIterator($sheet, 1, 1)->current();
      call_user_func($this->schemaValidator, $titleRow, $this->map);
    }

    $handler = $this->getServiceConfiguration()->getTransferHandler($this->getSubject());

    $key = null;
    if (isset($handler->options['key']))
    {
      $key = $handler->options['key'];
    }

    foreach ($this->getRowIterator($sheet, $this->skip_rows + 1) as $row)
    {
      $this->parseRow($row, $key);
    }
  }

  /**
   * @param Worksheet $sheet
   * @param int|string $startRow
   * @param null $endRow
   */
  protected function getRowIterator($sheet, $startRow = 1, $endRow = null)
  {
    if ($this->source == 'column')
    {
      if (is_int($startRow))
      {
        $startRow = chr(ord('A') + $startRow - 1);
      }
      if (is_int($endRow))
      {
        $endRow = chr(ord('A') + $endRow - 1);
      }

      return $sheet->getColumnIterator($startRow, $endRow);
    }

    return $sheet->getRowIterator($startRow, $endRow);
  }

  public function parseRow($row, $key = null)
  {
    $entity = $this->mapRowToEntity($row);

    if ($key && !$entity->getValue($key))
    {
      return;
    }

    $this->entities->add($entity);

    if (count($this->childParsers))
    {
      $this->parseChilds($row);
    }
  }

  public function serialize ($objects)
  {
    return '';
  }

  public function getEntities ()
  {
    $childEntities = array($this->getSubject() => $this->entities);

    foreach ($this->childParsers as $subject => $childParser)
    {
      if ($childParser !== $this)
      {
        $e = $childParser["parser"]->getEntities();

        if (is_array($e))
        {
          $childEntities = array_merge($childEntities, $e);
        }
        else
        {
          $childEntities[$subject] = $e;
        }
      }
      else
      {
        $this->entities->add($childParser["parser"]->getEntities());
      }
    }

    return $childEntities;
  }

  protected function addChildParser($subject, $parser, $options)
  {
    $this->childParsers[$subject] = array("parser" => $parser, "options" => $options);
  }

  /**
   * @param Row|Column $row
   * @return BaseEntity
   */
  private function mapRowToEntity($row)
  {
    $cells = $row->getCellIterator();
    /** @var BaseEntity $entity */
    $entity = $this->createEntity();
    $data = [];

    foreach ($cells as $i => $cell)
    {
      $cellName = $i;

      if ($this->map)
      {
        /*
         * Если есть во что мапить, то мапим,
         *  иначе просто по названием колонок вернем
         */
        if (isset($this->map[$i]))
        {
          $cellName = $this->map[$i];
        }
        else
        {
          continue;
        }
      }

      $data[$cellName] = $cell->getCalculatedValue();
    }

    $entity->parse($data);
    return $entity;
  }

  protected function parseChilds($row)
  {
    foreach ($this->childParsers as $subject => $data)
    {
      /** @var BaseParser|XlsxParser $parser */
      $parser = $data['parser'];
      $options = $data['options'];
      $parser->parseRow($row, $options['key']);
    }
  }
}