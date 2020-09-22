<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Geo\Region;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;

class FeedbackCommand extends ContainerAwareCommand
{
  /**
   *
   */
  protected function configure()
  {
    $this
      ->setName('feedback:iblock')
      ->setDescription('Set feedback');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|void|null
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

    $this->fillRegion($entityManager);
//    $this->fillFeedback($entityManager);
//    $this->fillCompany($entityManager);
//    $this->fillCompanyBranch($entityManager);
  }

  /**
   * @param $entityManager
   */
  private function fillRegion($entityManager)
  {
    $conn = $entityManager->getConnection();
    $stmt = $conn->prepare('SELECT * FROM b_iblock_section WHERE IBLOCK_ID = 16 AND ACTIVE = "Y"');
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $name = !empty($item['SEARCHABLE_CONTENT']) ? $item['SEARCHABLE_CONTENT'] : null;
      $code = !empty($item['CODE']) ? $item['CODE'] : null;

      $region = new Region();
      $region->setName($name);
      $region->setCode($code);
      $entityManager->persist($region);
    }

    $entityManager->flush();
    $entityManager->commit();
  }

  /**
   * @param $entityManager
   */
  private function fillCompany($entityManager)
  {
    $conn = $entityManager->getConnection();
    $stmt = $conn->prepare('SELECT * FROM b_iblock_element WHERE IBLOCK_ID = 24 AND ACTIVE = "Y"');
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $name = !empty($item['NAME']) ? $item['NAME'] : null;
      $company = new Company();
      $company->setName($name);
      $entityManager->persist($company);
    }

    $entityManager->flush();
    $entityManager->commit();
  }

  /**
   * @param $entityManager
   */
  private function fillCompanyBranch($entityManager)
  {
    $conn = $entityManager->getConnection();
    $stmt = $conn->prepare('SELECT * FROM b_iblock_element WHERE IBLOCK_ID = 16 AND ACTIVE = "Y"');
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $name = !empty($item['NAME']) ? $item['NAME'] : null;
      $code = !empty($item['CODE']) ? $item['CODE'] : null;

      $branch = new CompanyBranch();
      $branch->setName($name);
      $branch->setCode($code);
      $entityManager->persist($branch);
    }

    $entityManager->flush();
    $entityManager->commit();
  }

  /**
   * @param $entityManager
   */
  private function fillFeedback($entityManager)
  {
    $conn = $entityManager->getConnection();
    $stmt = $conn->prepare('SELECT * FROM b_iblock_element WHERE IBLOCK_ID = 13 AND ACTIVE = "Y"');
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {
      $head = !empty($item['NAME']) ? $item['NAME'] : null;
      $text = !empty($item['SEARCHABLE_CONTENT']) ? $item['SEARCHABLE_CONTENT'] : null;
      $created = !empty($item['DATE_CREATE']) ? $item['DATE_CREATE'] : null;
      $date = !empty($created) ? DateTime::createFromFormat('Y-m-d H:m:s', $created) : null;

      $model = new Feedback();
      $model->setTitle($head);
      $model->setText($text);
      $model->setCreatedAt($date);
      $model->setUpdatedAt($date);
      $entityManager->persist($model);
    }

    $entityManager->flush();
    $entityManager->commit();
  }
}
