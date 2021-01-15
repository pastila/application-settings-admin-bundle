<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyStatus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class UpdateLogoCompanyCommand
 * @package AppBundle\Command
 */
class UpdateLogoCompanyCommand extends ContainerAwareCommand
{
  /**
   *
   */
  protected function configure()
  {
    $this
      ->setName('update:logo_company')
      ->setDescription('Update logo company from bitrix');
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
    $io->success('start update logo companies and branch');

    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $doctrine = $this->getContainer()->get('doctrine');
    $conn = $entityManager->getConnection();

    $companies = $entityManager->getRepository(Company::class)
      ->findBy(['status' => CompanyStatus::ACTIVE]);

    foreach ($companies as $company)
    {
      /**
       * @var Company $company
       */
      $imageBitrixId = $company->getFile();

      $sql = 'SELECT *
            FROM b_file bf  
            WHERE bf.ID = :ID';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':ID', $imageBitrixId);
      $stmt->execute();
      $model = $stmt->fetch();

      if (empty($model))
      {
        $io->error(sprintf('Not found file in database for company %s:', $company->getName()));
      }

      try
      {
        $fileSubDir = $model['SUBDIR'];
        $fileNameFull = $model['FILE_NAME'];
        $baseDir = $this->getContainer()->get('kernel')->getRootDir() . '/..';
        $folder = $baseDir . '/web/upload/' . $fileSubDir . '/';
        $filePathFull = $folder . $fileNameFull;
        if (!file_exists($filePathFull))
        {
          throw new FileNotFoundException(sprintf('Not found file "%s" in folder for company %s:', $filePathFull, $company->getName()));
        }
        if (!file_exists($folder)) {
          mkdir($folder, 0777, true);
        }
      } catch (FileNotFoundException $exception)
      {
        $io->error($exception->getMessage());
        continue;
      } catch (\Exception $exception)
      {
        $io->error(sprintf('Exception create path for file for company %s: %s', $company->getName(), $exception));
        continue;
      }

      $newFilePathFull = $baseDir . '/web/uploads/companies/' . $fileNameFull;
      echo $newFilePathFull;

      if (!copy($filePathFull, $newFilePathFull))
      {
        $io->error(sprintf('Not copy file "%s" for company %s', $filePathFull, $company->getName()));
        continue;
      }
      die;
    }

    $io->success('finish update logo companies and branch');
  }
}
