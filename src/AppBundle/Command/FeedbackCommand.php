<?php

namespace AppBundle\Command;

use AppBundle\Entity\Company\Feedback;
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
  protected function configure ()
  {
    $this
      ->setName('feedback:iblick')
      ->setDescription('Set feedback');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|void|null
   */
  protected function execute (InputInterface $input, OutputInterface $output)
  {
    $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    $conn = $entityManager->getConnection();

    $stmt = $conn->prepare('SELECT * FROM b_iblock_element WHERE IBLOCK_ID = 13 AND ACTIVE = "Y"');
    $stmt->execute();

    $result = $stmt->fetchAll();
    foreach ($result as $item) {

      $head = !empty($item['NAME']) ? $item['NAME'] : null;
      $text = !empty($item['SEARCHABLE_CONTENT']) ? $item['SEARCHABLE_CONTENT'] : null;
      $created = !empty($item['DATE_CREATE']) ? $item['DATE_CREATE'] : null;

      $model = new Feedback();
      $model->setHead($head);
      $model->setText($text);
    }
  }
}
