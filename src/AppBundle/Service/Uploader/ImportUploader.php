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

namespace AppBundle\Service\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportUploader
{
  private $dir;

  /**
   * InvitationImportUploader constructor.
   * @param string $dir
   */
  public function __construct ($dir)
  {
    $this->dir = $dir;
  }

  /**
   * @param UploadedFile $file
   * @return string absolute path to file
   */
  public function upload(UploadedFile $file)
  {
    if (!is_dir($this->dir))
    {
      mkdir($this->dir, 0777, true);
    }

    $path = realpath(sprintf('%s%s', $this->dir, DIRECTORY_SEPARATOR));
    $file = $file->move($path, sprintf('%s.xlsx', uniqid(date('Y-m-d'))));

    return $file->getPathname();
  }
}