<?php


namespace AppBundle\Model\Obrashchenia;


use AppBundle\Entity\User\User;
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
   * Автор отзыва
   * @var User
   */
  private $author;

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

  /**
   * @return mixed
   */
  public function getAuthor()
  {
    return $this->author;
  }

  /**
   * @param $author
   */
  public function setAuthor($author): void
  {
    $this->author = $author;
  }
}