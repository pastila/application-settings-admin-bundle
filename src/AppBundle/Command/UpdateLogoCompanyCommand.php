<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\InsuranceCompany;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
   * @return int|void|null
   * @throws \Doctrine\DBAL\DBALException
   * @throws \Doctrine\ORM\ORMException
   * @throws \Doctrine\ORM\OptimisticLockException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);
    $io->success('start update logo companies and branch');

    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $conn = $entityManager->getConnection();

    $companies = $entityManager
      ->getRepository(InsuranceCompany::class)
      ->findAll();

    foreach ($companies as $company)
    {
      /**
       * @var InsuranceCompany $company
       */
      $imageBitrixId = $company->getLogo();

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
        continue;
      }

      try
      {
        $fileSubDir = $model['SUBDIR'];
        $fileName = $model['FILE_NAME'];
        $baseDir = $this->getContainer()->get('kernel')->getRootDir() . '/..';
        $folder = $baseDir . '/web/upload/' . $fileSubDir . '/';
        $filePathFull = $folder . $fileName;
        if (!file_exists($filePathFull))
        {
          throw new FileNotFoundException(sprintf('Not found file "%s" in folder for company %s:', $filePathFull, $company->getName()));
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
      $dirSection = 'companies';
      $getMime = explode('.', $fileName);
      $mime = strtolower(end($getMime));
      $newFileName = md5(uniqid()) . '.' . $mime;
      $folder = $baseDir . '/web/uploads/' . $dirSection;
      $newFilePathFull = $folder . '/' . $newFileName;

      if (!file_exists($folder))
      {
        @mkdir($folder, 0777, true);
      }
      if (!copy($filePathFull, $newFilePathFull))
      {
        $io->error(sprintf('Not copy file "%s" for company %s', $filePathFull, $company->getName()));
        continue;
      }

      $uploadedFile = new UploadedFile(
        $filePathFull,
        $fileName,
        null,
        filesize($filePathFull)
      );

      $company->setLogo($uploadedFile);
      $entityManager->persist($company);
      $entityManager->flush();
    }

    $io->success('finish update logo companies and branch');
  }
}
