<?php


namespace AppBundle\Model\Obrashchenia;


use AppBundle\Entity\Obrashcheniya\ObrashcheniyaEmail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ObrashcheniaBranch
{
  /**
   * Входные данные для пасинга
   */
  private $data;
  /**
   * Путь до файла обращения
   */
  private $pdf;
  /**
   * Список email адерсов на которые отправить письма
   */
  private $emailsTo;
  /**
   * Прикрепленные файлы
   */
  private $filesAttach;
  /**
   * Директория, где лежат обращения
   */
  const BASE_PATH_FILE = '/var/www/var/uploads/pdf/';
  /**
   * Директория, где лежат прикрепленные файлы
   */
  const BASE_PATH_ATTACH = '/var/www/web';

  /**
   * ObrashcheniaBranch constructor.
   * @param $data
   */
  public function __construct($data)
  {
    $this->data = $data;
    $this->parsing();
  }

  private function parsing()
  {
    if (
      empty($this->data[2]['EMAIL']) ||
      empty($this->data[2]['PDF'])
    )
    {
      throw new NotFoundHttpException('Empty data in ObrashcheniaBranch');
    }

    $this->pdf = self::BASE_PATH_FILE . $this->data[2]['PDF'];

    $this->emailsTo = array_map(function ($item)
    {
      return trim($item);
    }, explode(',', $this->data[2]['EMAIL']));

    for ($i = 1; $i <= 5; $i++)
    {
      $file = $this->data[2]['PROPERTY_IMG_' . $i . '_VALUE'];
      if (!empty($file))
      {
        $this->filesAttach[] = self::BASE_PATH_ATTACH . $file;
      }
    }
  }

  /**
   * @return mixed
   */
  public function getFilesAttach()
  {
    return $this->filesAttach;
  }

  /**
   * @param mixed $filesAttach
   */
  public function setFilesAttach($filesAttach): void
  {
    $this->filesAttach = $filesAttach;
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