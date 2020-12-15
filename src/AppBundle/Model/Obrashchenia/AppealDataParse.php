<?php


namespace AppBundle\Model\Obrashchenia;


use AppBundle\Entity\User\User;
use AppBundle\Repository\Obrashcheniya\ObrashcheniyaFileRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    $model = new AppealDataToCompany();
    $model->setAuthor($author->getFullName());
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
