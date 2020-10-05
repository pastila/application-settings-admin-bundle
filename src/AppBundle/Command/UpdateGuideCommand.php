<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use AppBundle\Helper\Feedback\CommonHelper;
use AppBundle\Helper\Feedback\CompanyBranchHelper;
use AppBundle\Helper\Feedback\CompanyHelper;
use AppBundle\Helper\Feedback\RegionHelper;
use AppBundle\Helper\Feedback\UserHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class UpdateGuideCommand
 * @package AppBundle\Command
 */
class UpdateGuideCommand extends ContainerAwareCommand
{
  /**
   *
   */
  protected function configure()
  {
    $this
      ->setName('update:guide_from_bitrix')
      ->setDescription('Update the guide from Bitrix');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|void
   * @throws \Doctrine\DBAL\DBALException
   * @throws \Throwable
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $logger = $this->getContainer()->get('logger');

    $common = new CommonHelper();
    $common->clearTable($entityManager, [Region::class]);
    $regionHelper = new RegionHelper($entityManager,$logger);
    $res = $regionHelper->load();
    $regionHelper->check();
    $io = new SymfonyStyle($input, $output);
    $io->success('Region:' . $res);

    $common = new CommonHelper();
    $common->clearTable($entityManager, [Company::class]);
    $companyHelper = new CompanyHelper($entityManager,$logger);
    $res = $companyHelper->load();
    $companyHelper->check();
    $io = new SymfonyStyle($input, $output);
    $io->success('Company:' . $res);

    $common = new CommonHelper();
    $common->clearTable($entityManager, [CompanyBranch::class]);
    $companyBranchHelper = new CompanyBranchHelper($entityManager,$logger);
    $res = $companyBranchHelper->load();
    $companyBranchHelper->check();
    $io = new SymfonyStyle($input, $output);
    $io->success('CompanyBranch:' . $res);

//    $common = new CommonHelper(); TODO требуется именно обновление
//    $common->clearTable($entityManager, [User::class]);
//    $userHelper = new UserHelper($entityManager,$logger);
//    $userHelper->load();
//    $userHelper->check();
  }
}
