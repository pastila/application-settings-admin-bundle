<?php


namespace AppBundle\Model\Obrashchenia;


use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFileType;
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
      empty($data['email']) ||
      empty($data['id']) ||
      empty($data['login'])
    )
    {
      throw new \InvalidArgumentException('Empty EMAIL or login or ID in data in AppealDataParse');
    }
    $author = $this->entityManager->getRepository(User::class)
      ->findOneBy(['login' => $data['login']]);
    if (!$author)
    {
      throw new \InvalidArgumentException(sprintf('Not found user %s by login in AppealDataParse', $data['login']));
    }
    $pdfFile = $this->entityManager
      ->getRepository(ObrashcheniyaFile::class)
      ->createFileQueryBuilder($data['id'])
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
    if (!$pdfFile)
    {
      throw new \Exception(sprintf('Not found file appeal %s by ID in AppealDataParse', $data['id']));
    }
    $attachedFiles = $this->entityManager
      ->getRepository(ObrashcheniyaFile::class)
      ->createFileQueryBuilder($data['id'], null, null, ObrashcheniyaFileType::ATTACH)
      ->getQuery()
      ->getResult();
    if (!$attachedFiles)
    {
      throw new \Exception(sprintf('Not found files %s by ID in AppealDataParse',$data['id']));
    }

    $model = new AppealDataToCompany();
    $model->setEmailUser($author->getEmail());
    $model->setBitrixId($data['id']);
    $model->setAuthor($author->getFullName());
    $model->setPdf($pdfFile->getFile());
    $model->setEmailsTo(array_map(function ($item)
    {
      return trim($item);
    }, explode(',', $data['email'])));

    $attached = [];
    foreach ($attachedFiles as $file)
    {
      /**
       * @var ObrashcheniyaFile $file
       */
      if (!file_exists($file->getFile()))
      {
        throw new FileNotFoundException(sprintf('Not found attached file %s in parsing appeal', $file->getFile()));
      }
      $attached[] = $file->getFile();
    }
    $model->setAttachedFiles($attached);

    return $model;
  }
}
