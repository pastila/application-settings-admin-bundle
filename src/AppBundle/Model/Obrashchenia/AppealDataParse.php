<?php


namespace AppBundle\Model\Obrashchenia;


use AppBundle\Repository\Obrashcheniya\ObrashcheniyaFileRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppealDataParse
{
  /**
   * Директория, где лежат pdf файлы обращения
   * @var string
   */
  private $appealPathPdf;
  /**
   * Директория, где лежат прикрепленные файлы
   * @var string
   */
  private $appealPathAttached;
  /**
   * @var ObrashcheniyaFileRepository
   */
  private $fileRepository;

  public function __construct(
    $appealPathPdf,
    $appealPathAttached,
    ObrashcheniyaFileRepository $fileRepository
  )
  {
    $this->appealPathPdf = $appealPathPdf;
    $this->appealPathAttached = $appealPathAttached;
    $this->fileRepository = $fileRepository;
  }

  /**
   * @param $data
   * @return AppealDataToCompany
   */
  public function parse($data)
  {
    if (
      empty($data[2]['EMAIL']) ||
      empty($data[2]['PDF'])
    )
    {
      throw new NotFoundHttpException('Empty data in AppealDataToCompany');
    }
    $model = new AppealDataToCompany();
    $model->setPdf($this->appealPathPdf . $data[2]['PDF']);
    $model->setEmailsTo(array_map(function ($item)
    {
      return trim($item);
    }, explode(',', $data[2]['EMAIL'])));

    $filesAttach = [];
    for ($i = 1; $i <= 5; $i++)
    {
      $file = $data[2]['PROPERTY_IMG_' . $i . '_VALUE'];
      if (!empty($file))
      {
        $filesAttach[] = $this->appealPathAttached . $file;
      }
    }
    $model->setAttachedFiles($filesAttach);

    return $model;
  }
}