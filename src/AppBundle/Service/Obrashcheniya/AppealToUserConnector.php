<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Service\Obrashcheniya;


use AppBundle\Entity\Obrashcheniya\ObrashcheniyaFile;
use AppBundle\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppealToUserConnector
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  /**
   * @param $data
   */
  public function saveAppealToUserConnection($data)
  {
    if (empty($data))
    {
      throw new \InvalidArgumentException('Empty body from Obrashcheniya Files');
    }

    $resolver = new OptionsResolver();
    $resolver->setRequired([
      'user_id',
      'user_login',
      'file_name',
      'obrashcheniya_id',
      'file_type'
    ]);

    $resolver->setDefaults([
      'imageNumber' => null
    ]);

    $data = $resolver->resolve($data);

    $author = $this->entityManager->getRepository(User::class)
      ->findOneBy(['login' => $data['user_login']]);

    $model = new ObrashcheniyaFile();

    $model->setAuthor($author);
    $model->setType($data['file_type']);
    $model->setFile($data['file_name']);
    $model->setBitrixId($data['obrashcheniya_id']);
    $model->setImageNumber($data['imageNumber']);

    $this->entityManager->persist($model);
    $this->entityManager->flush();
  }
}
