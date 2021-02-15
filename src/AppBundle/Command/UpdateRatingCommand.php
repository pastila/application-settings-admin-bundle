<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class UpdateRatingCommand
 * @package AppBundle\Command
 */
class UpdateRatingCommand extends ContainerAwareCommand
{
  /**
   *
   */
  protected function configure()
  {
    $this
      ->setName('update:rating')
      ->setDescription('Update rating for company and branch');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|void
   * @throws \Doctrine\DBAL\DBALException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);

    $em = $this->getContainer()->get('doctrine.orm.entity_manager');
    $conn = $em->getConnection();

    $io->text('start update rating branches');
    try
    {
      $sql = 'UPDATE s_company_branches cb
            LEFT JOIN
            (
                SELECT branch_id, AVG (valuation) as valuation_avg
                FROM s_company_feedbacks f
                WHERE f.moderation_status > 0 AND f.valuation
                GROUP BY f.branch_id
            ) f ON f.branch_id = cb.id
            SET cb.valuation = f.valuation_avg';
      $stmt = $conn->prepare($sql);
      $stmt->execute();
    }
    catch (\Exception $exception)
    {
      $io->error($exception);
      return;
    }
    $io->success('finish update rating branches');

    $io->text('start update rating companies');
    try
    {
      $sql = 'UPDATE s_companies sc
            LEFT JOIN
            (
                SELECT company_id, AVG (valuation) as valuation_avg
                FROM s_company_branches scb
                WHERE scb.published = true AND scb.valuation > 0
                GROUP BY scb.company_id
            ) scb ON scb.company_id = sc.id
            SET sc.valuation = scb.valuation_avg';
      $stmt = $conn->prepare($sql);
      $stmt->execute();
    }
    catch (\Exception $exception)
    {
      $io->error($exception);
      return;
    }
    $io->success('finish update rating companies');
  }
}
