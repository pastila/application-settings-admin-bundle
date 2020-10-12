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
    $io = new SymfonyStyle($input, $output);
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $logger = $this->getContainer()->get('logger');

    $common = new CommonHelper();
    $common->clearTable($entityManager, [Region::class]);
    $regionHelper = new RegionHelper($entityManager,$logger);
    $res = $regionHelper->load();
    $regionHelper->check();
    $io->success('Region:' . $res);

    $common = new CommonHelper();
    $common->clearTable($entityManager, [Company::class]);
    $companyHelper = $this->getContainer()->get('AppBundle\Helper\Feedback\CompanyHelper');
    $companyHelper->load();
    $companyHelper->check();

    $common = new CommonHelper();
    $common->clearTable($entityManager, [CompanyBranch::class]);
    $companyBranchHelper = $this->getContainer()->get('AppBundle\Helper\Feedback\CompanyBranchHelper');
    $companyBranchHelper->load($io);
    $companyBranchHelper->check();
  }
}
