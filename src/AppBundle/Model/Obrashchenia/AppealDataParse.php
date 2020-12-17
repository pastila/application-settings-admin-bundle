<?php


namespace AppBundle\Model\Obrashchenia;


use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\User\User;
use AppBundle\Repository\Obrashcheniya\ObrashcheniyaFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppealDataParse
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;
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
    EntityManagerInterface $entityManager,
    $appealPathPdf,
    $appealPathAttached,
    ObrashcheniyaFileRepository $fileRepository
  )
  {
    $this->entityManager = $entityManager;
    $this->appealPathPdf = $appealPathPdf;
    $this->appealPathAttached = $appealPathAttached;
    $this->fileRepository = $fileRepository;
  }

  /**
   * @param $data
   * @return AppealDataToCompany
   * @throws \Exception
   */
  public function parse($data)
  {
    if (
      empty($data[2]['EMAIL']) ||
      empty($data[2]['PDF'] ||
      empty($data['login']))
    )
    {
      throw new \InvalidArgumentException('Empty EMAIL or PDF in data in AppealDataParse');
    }
    $author = $this->entityManager->getRepository(User::class)
      ->findOneBy(['login' => $data['login']]);
    if (!$author)
    {
      throw new \InvalidArgumentException(sprintf('Not found user %s by login in AppealDataParse', $data['login']));
    }
    $files = $this->entityManager->getRepository(ObrashcheniyaFile::class)
      ->findBy(['bitrixId' => $data[2]['ID']]);
    if (!$files)
    {
      throw new \Exception(sprintf('Not found files %s by ID in AppealDataParse', $data[2]['ID']));
    }

    $model = new AppealDataToCompany();
    $model->setAuthor($author->getFullName());
    $model->setEmailsTo(array_map(function ($item)
    {
      return trim($item);
    }, explode(',', $data[2]['EMAIL'])));

    $attached = [];
    foreach ($files as $file)
    {
      if (!file_exists($file->getFile()))
      {
        throw new FileNotFoundException(sprintf('Not found file %s in parsing appeal', $file->getFile()));
      }
      /**
       * @var ObrashcheniyaFile $file
       */
      if ($file->getImageNumber() === null)
      {
        $model->setPdf($file->getFile());
      } else
      {
        $attached[] = $file->getFile();
      }
    }
    $model->setAttachedFiles($attached);

    return $model;
  }
}
