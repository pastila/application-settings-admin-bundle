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
  private $attachedFiles = [];
  /**
   * Автор отзыва
   * @var User
   */
  private $author;
  /**
   * @var string
   */
  private $bitrixId;
  /**
   * Email адреас пользователя
   * @var string
   */
  private $emailUser;

  /**
   * @return string
   */
  public function getBitrixId(): string
  {
    return $this->bitrixId;
  }

  /**
   * @param string $bitrixId
   */
  public function setBitrixId(string $bitrixId): void
  {
    $this->bitrixId = $bitrixId;
  }

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
   * @return mixed|null
   */
  public function getEmailSend()
  {
    if (is_array($this->emailsTo))
    {
      foreach ($this->emailsTo as $email)
      {
        if (!empty($email))
        {
          return $email;
        }
      }
    }

    return null;
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

  /**
   * @return string
   */
  public function getEmailUser(): string
  {
    return $this->emailUser;
  }

  /**
   * @param string $emailUser
   */
  public function setEmailUser(string $emailUser): void
  {
    $this->emailUser = $emailUser;
  }
}