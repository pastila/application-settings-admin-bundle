<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\InsuranceCompany;
use AppBundle\Entity\Company\InsuranceCompanyBranch;
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

    $regionHelper = $this->getContainer()->get('AppBundle\Helper\Feedback\RegionHelper');
    $regionHelper->load($io);
    $regionHelper->check();

    $companyHelper = $this->getContainer()->get('AppBundle\Helper\Feedback\CompanyHelper');
    $companyHelper->load($io);
    $companyHelper->check();

    $companyBranchHelper = $this->getContainer()->get('AppBundle\Helper\Feedback\CompanyBranchHelper');
    $companyBranchHelper->load($io);
    $companyBranchHelper->check();
  }
}
