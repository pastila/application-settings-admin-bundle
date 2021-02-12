<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
use AppBundle\Entity\Geo\Region;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * У каждой компании будут созданы филиалы во всех регионах,
 * если его еще нет, но без статуса публикации
 *
 * Class InsertBranchesToCompaniesCommand
 * @package AppBundle\Command
 */
class InsertBranchesToCompaniesCommand extends ContainerAwareCommand
{
  /**
   *
   */
  protected function configure()
  {
    $this
      ->setName('insert:branches_to_companies')
      ->setDescription('Insert branches to companies');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|void|null
   * @throws \Doctrine\ORM\ORMException
   * @throws \Doctrine\ORM\OptimisticLockException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);
    $io->success('start insert branches to companies');

    /**
     * @var EntityManager $em
     */
    $em = $this->getContainer()->get('doctrine.orm.entity_manager');
    $companies = $em->getRepository(InsuranceCompany::class)
      ->findAll();

    $regions = $em->getRepository(Region::class)
      ->createQueryBuilder('r')
      ->orderBy('r.name', 'ASC')
      ->getQuery()
      ->getResult();

    foreach ($companies as $company)
    {
      foreach ($regions as $region)
      {
        $branch = $em->getRepository(InsuranceCompanyBranch::class)
          ->findOneBy([
            'region' => $region,
            'company' => $company
          ]);

        if (!$branch)
        {
          $branch = new InsuranceCompanyBranch();
          $branch->setRegion($region);
          $branch->setCompany($company);
          $branch->setKpp($company->getKpp());
          $branch->setPublished(false);
          $company->addBranch($branch);
        }
      }
      $em->persist($company);
    }
    $em->flush();

    $io->success('end insert branches to companies');
  }
}
