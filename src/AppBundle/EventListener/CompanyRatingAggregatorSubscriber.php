<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Repository\Company\InsuranceCompanyBranchRepository;
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
  private $removedFeedbacks;
  private $removedCompanyBranches;

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

    $this->removedFeedbacks = new ArrayCollection();
    $this->removedCompanyBranches = new ArrayCollection();
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

  /**
   * @param LifecycleEventArgs $args
   */
  public function postPersist(LifecycleEventArgs $args)
  {
    $this->updateRating($args);
  }

  /**
   * @param LifecycleEventArgs $args
   */
  public function postUpdate(LifecycleEventArgs $args)
  {
    $this->updateRating($args);
  }

  /**
   * https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/events.html#lifecycle-events
   * Вызов flush в postRemove может приводить к uninitializable index
   * Поэтому согласно документации требуется сохранении коллекции над которой требуется работа
   * И вызов уже вне Lifecycle, чем является postFlush
   * @param LifecycleEventArgs $args
   */
  public function postRemove(LifecycleEventArgs $args)
  {
    $this->deferredRatingUpdate($args);
  }

  /**
   * @param PostFlushEventArgs $args
   */
  public function postFlush(PostFlushEventArgs $args)
  {
    if (!$this->removedFeedbacks->isEmpty())
    {
      foreach ($this->removedFeedbacks->getValues() as $feedback)
      {
        $this->removedFeedbacks->removeElement($feedback);
        $this->updateBranchRating($feedback);
      }
    }
    if (!$this->removedCompanyBranches->isEmpty())
    {
      foreach ($this->removedCompanyBranches->getValues() as $companyBranches)
      {
        $this->removedCompanyBranches->removeElement($companyBranches);
        $this->updateCompanyRating($companyBranches);
      }
    }
  }

  /**
   * @param LifecycleEventArgs $args
   */
  protected function updateRating(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    if ($entity instanceof Feedback)
    {
      $this->updateBranchRating($entity);
    }

    if ($entity instanceof InsuranceCompanyBranch)
    {
      $this->updateCompanyRating($entity);
    }
  }

  /**
   * Добавление сущности в коллекцию для обновление рейтинга Компании и Филиала
   * @param LifecycleEventArgs $args
   */
  protected function deferredRatingUpdate(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    if ($entity instanceof Feedback)
    {
      if (!$this->removedFeedbacks->contains($entity))
      {
        $this->removedFeedbacks->add($entity);
      }
    }

    if ($entity instanceof InsuranceCompanyBranch)
    {
      if (!$this->removedCompanyBranches->contains($entity))
      {
        $this->removedCompanyBranches->add($entity);
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
      $this->logger->info(sprintf('Updating branch rating: %s', $branch->getKpp()));

      $v = $this->computeValuationForBranch($branch);
      $branch->setValuation($v);

      $this->logger->info(sprintf('Calculated rating is: %s', $branch->getValuation()));

      $this->em->persist($branch);
      $this->em->flush();
    }
  }

  /**
   * @param InsuranceCompanyBranch $companyBranches
   */
  protected function updateCompanyRating($companyBranches)
  {
    $company = $companyBranches->getCompany();
    if ($company)
    {
      $this->logger->info(sprintf('Updating company rating: %s', $company->getKpp()));

      $currentValuation = $company->getValuation();

      $v = $this->computeValuationForCompany($company);

      $this->logger->info(sprintf('Calculated rating is: %s', $v));

      if ($v !== $currentValuation)
      {
        $this->logger->info(sprintf('Updated company %s valuation: %s', $company->getKpp(), $v));

        $company->setValuation($v);

        $this->em->persist($company);
        $this->em->flush();
      }
      else
      {
        $this->logger->info(sprintf('Company %s valuation has not changed', $company->getKpp()));
      }
    }
  }

  protected function computeValuationForBranch(InsuranceCompanyBranch $branch)
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

  protected function computeValuationForCompany(InsuranceCompany $company)
  {
    return $this
      ->em->getRepository(InsuranceCompanyBranch::class)
      ->createQueryBuilder('cb')
      ->select('avg(cb.valuation)')
      ->where('cb.company = :company AND cb.published > 0 AND cb.valuation > 0')
      ->setParameter(':company', $company)
      ->getQuery()
      ->getSingleScalarResult();
  }
}
