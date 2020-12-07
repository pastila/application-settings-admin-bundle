<?php


namespace AppBundle\Model\Obrashchenia;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppealDataToCompany
{
  /**
   * Путь до файла обращения
   * @var string
   */
  private $pdf;
  /**
   * Список email адерсов на которые отправить письма
   * @var array
   */
  private $emailsTo;
  /**
   * Прикрепленные файлы
   * @var array
   */
  private $attachedFiles;

  /**
   * @return mixed
   */
  public function getAttachedFiles()
  {
    return $this->attachedFiles;
  }

  /**
   * @param mixed $attachedFiles
   */
  public function setAttachedFiles($attachedFiles): void
  {
    $this->attachedFiles = $attachedFiles;
  }

  /**
   * @return mixed
   */
  public function getEmailsTo()
  {
    return $this->emailsTo;
  }

  /**
   * @param mixed $emailsTo
   */
  public function setEmailsTo($emailsTo): void
  {
    $this->emailsTo = $emailsTo;
  }

  /**
   * @return mixed
   */
  public function getPdf()
  {
    return $this->pdf;
  }

  /**
   * @param mixed $pdf
   */
  public function setPdf($pdf): void
  {
    $this->pdf = $pdf;
  }
}