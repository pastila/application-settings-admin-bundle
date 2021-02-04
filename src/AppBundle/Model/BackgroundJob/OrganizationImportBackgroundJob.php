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

namespace AppBundle\Model\BackgroundJob;

use Accurateweb\TaskSchedulerBundle\Model\MetaData;
use Accurateweb\TaskSchedulerBundle\Service\BackgroundJob\BackgroundJobInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationImportBackgroundJob implements BackgroundJobInterface
{
  const CLSID = '88436faa-55aa-4f76-a491-47b750b2b939';

  private $filename;
  private $subject;
  private $year;
  /**
   * @var string|null
   */
  private $operatorEmail;

  public function __construct($operatorEmail)
  {
    $this->operatorEmail = $operatorEmail;
  }

  public function initialize(MetaData $data)
  {
    $this->resolveCmdOptions($data->getCmdOptions());
    $this->resolveOptions($data->getOptions());
  }

  public function getCommand()
  {
    if (!$this->filename)
    {
      throw new \LogicException();
    }

    return sprintf('synchronization:run --datasource=local --filename=\'%s\' --year=%s %s', $this->filename, $this->year, $this->subject);
  }

  public function getName()
  {
    return 'Импорт медицинских организаций';
  }

  public function getClsid()
  {
    return static::CLSID;
  }

  private function resolveOptions($opts)
  {
    $options = new OptionsResolver();
    $options->setDefault('email', null);
    $options = $options->resolve($opts);
    $this->operatorEmail = $options['email'] ? $options['email'] : $this->operatorEmail;
  }

  private function resolveCmdOptions($options)
  {
    $cmdOptions = new OptionsResolver();
    $cmdOptions->setRequired(['filename', 'year']);
    $cmdOptions->setDefault('subject', 'organization_list');
    $cmdOptions = $cmdOptions->resolve($options);
    $this->filename = $cmdOptions['filename'];
    $this->subject = $cmdOptions['subject'];
    $this->year = $cmdOptions['year'];
  }
}