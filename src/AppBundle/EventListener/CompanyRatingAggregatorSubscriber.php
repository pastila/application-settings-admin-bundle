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
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

class CompanyRatingAggregatorSubscriber implements EventSubscriber
{
  private $logger;

  private $em;

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
  }

  /**
   * @return array
   */
  public function getSubscribedEvents()
  {
    return [
      Events::postPersist,
      Events::postUpdate,
      Events::postRemove
    ];
  }

  public function postUpdate(LifecycleEventArgs $args)
  {
    $this->updateCompanyRating($args);
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $this->updateCompanyRating($args);
  }

  public function postRemove(LifecycleEventArgs $args)
  {
    $this->updateCompanyRating($args);
  }

  protected function updateCompanyRating(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    if ($entity instanceof Feedback)
    {
      $branch = $entity->getBranch();
      if ($branch)
      {
        $this->logger->info(sprintf('Updating branch rating: %s', $branch->getCode()));
        $branch->setValuation($this->computeValuationForBranch($branch));

        $this->logger->info(sprintf('Calculated rating is: %s', $branch->getValuation()));

        $this->em->persist($branch);
        $this->em->flush();
      }
    }

    if ($entity instanceof CompanyBranch)
    {
      $company = $entity->getCompany();
      if ($company)
      {

       $this->logger->info(sprintf('Updating company rating: %s', $company->getKpp()));
       $company->setValuation($this->computeValuationForCompany($company));

       $this->logger->info(sprintf('Calculated rating is: %s', $company->getValuation()));

        $this->em->persist($company);
        $this->em->flush();
      }
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
      ->where('cb.company = :company AND cb.valuation > 0')
      ->setParameter(':company', $company)
      ->getQuery()
      ->getSingleScalarResult();
  }
}
