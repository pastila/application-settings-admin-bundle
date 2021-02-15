<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\InsuranceCompanyBranch;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * В битриксе было дублирование,
 * хранилось несколько филиалов, но только один из них был опубликован
 *
 * Class DeleteUnusedBranchCommand
 * @package AppBundle\Command
 */
class DeleteUnusedBranchCommand extends ContainerAwareCommand
{
  /**
   *
   */
  protected function configure()
  {
    $this
      ->setName('delete:unused_branch')
      ->setDescription('Remove unused branches');
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
    $io->success('start remove branches');

    /**
     * @var EntityManager $em
     */
    $em = $this->getContainer()->get('doctrine.orm.entity_manager');
    $branches = $em->getRepository(InsuranceCompanyBranch::class)
      ->createQueryBuilder('cb')
      ->leftJoin('cb.feedbacks', 'f')
      ->where('cb.published = 0')
      ->having("count(f.id) = 0")
      ->groupBy('cb.id')
      ->getQuery()
      ->getResult();

    foreach ($branches as $branch)
    {
      $em->remove($branch);
    }
    $em->flush();

    $io->success('end remove branches, count delete: ' . count($branches));
  }
}
