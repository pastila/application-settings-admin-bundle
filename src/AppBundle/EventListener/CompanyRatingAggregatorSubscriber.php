<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Repository\Company\CompanyBranchRepository;
use AppBundle\Repository\Company\FeedbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

class CompanyRatingAggregatorSubscriber implements EventSubscriber
{
  private $logger;
  private $em;
  private $feedbacks;
  private $companyBranch;

  public function __construct(
    EntityManagerInterface $entityManager,
    LoggerInterface $logger//,
//    FeedbackRepository $feedbackRepository,
//    CompanyBranchRepository $companyBranchRepository
  )
  {
    $this->em = $entityManager;
    $this->logger = $logger;
//    $this->feedbackRepository = $feedbackRepository;
//    $this->companyBranchRepository = $companyBranchRepository;

    $this->feedbacks = new ArrayCollection();
    $this->companyBranch = new ArrayCollection();
  }

  /**
   * @return array
   */
  public function getSubscribedEvents()
  {
    return [
      Events::postPersist,
      Events::postUpdate,
      Events::postRemove,
      Events::postFlush
    ];
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    if ($entity instanceof Feedback)
    {
      $this->updateBranchRating($entity);
    }

    if ($entity instanceof CompanyBranch)
    {
      $this->updateCompanyRating($entity);
    }
  }

  public function postUpdate(LifecycleEventArgs $args)
  {
    $this->callbackUpdateRating($args);
  }

  public function postRemove(LifecycleEventArgs $args)
  {
    $this->callbackUpdateRating($args);
  }

  public function postFlush(PostFlushEventArgs $args)
  {
    if (!$this->feedbacks->isEmpty())
    {
      foreach ($this->feedbacks->getValues() as $feedback)
      {
        $this->feedbacks->removeElement($feedback);
        $this->updateBranchRating($feedback);
      }
    }
    if (!$this->companyBranch->isEmpty())
    {
      foreach ($this->companyBranch->getValues() as $companyBranch)
      {
        $this->companyBranch->removeElement($companyBranch);
        $this->updateCompanyRating($companyBranch);
      }
    }
  }

  protected function callbackUpdateRating(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    if ($entity instanceof Feedback)
    {
      if (!$this->feedbacks->contains($entity))
      {
        $this->feedbacks->add($entity);
      }
    }

    if ($entity instanceof CompanyBranch)
    {
      if (!$this->companyBranch->contains($entity))
      {
        $this->companyBranch->add($entity);
      }
    }
  }

  /**
   * @param Feedback $feedback
   */
  protected function updateBranchRating($feedback)
  {
    $branch = $feedback->getBranch();
    if ($branch)
    {
      $this->logger->info(sprintf('Updating branch rating: %s', $branch->getCode()));

      $v = $this->computeValuationForBranch($branch);
      $branch->setValuation($v);

      $this->logger->info(sprintf('Calculated rating is: %s', $branch->getValuation()));

      $this->em->persist($branch);
      $this->em->flush();
    }
  }

  /**
   * @param CompanyBranch $companyBranch
   */
  protected function updateCompanyRating($companyBranch)
  {
    $company = $companyBranch->getCompany();
    if ($company)
    {
      $this->logger->info(sprintf('Updating company rating: %s', $company->getKpp()));

      $v = $this->computeValuationForCompany($company);
      $company->setValuation($v);

      $this->logger->info(sprintf('Calculated rating is: %s', $company->getValuation()));

      $this->em->persist($company);
      $this->em->flush();
    }
  }

  protected function computeValuationForBranch(CompanyBranch $branch)
  {
    return $this
      ->em->getRepository(Feedback::class)
      ->createQueryBuilder('rv')
      ->select('avg(rv.valuation)')
      ->where('rv.branch = :branch AND rv.moderationStatus > 0 AND rv.valuation > 0')
      ->setParameter(':branch', $branch)
      ->getQuery()
      ->getSingleScalarResult();
  }

  protected function computeValuationForCompany(Company $company)
  {
    return $this
      ->em->getRepository(CompanyBranch::class)
      ->createQueryBuilder('cb')
      ->select('avg(cb.valuation)')
      ->where('cb.company = :company AND cb.status > 0 AND cb.valuation > 0')
      ->setParameter(':company', $company)
      ->getQuery()
      ->getSingleScalarResult();
  }
}
