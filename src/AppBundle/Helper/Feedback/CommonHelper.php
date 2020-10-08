<?php

namespace AppBundle\Helper\Feedback;

/**
 * Class Common
 * @package AppBundle\Helper\Feedback
 */
class CommonHelper
{
  /**
   * @param $entityManager
   * @param $tables
   */
  public function clearTable($entityManager, $tables)
  {
    $connection = $entityManager->getConnection();
    foreach ($tables as $table) {
      $metaData = $entityManager->getClassMetadata($table);
      $connection->query('SET FOREIGN_KEY_CHECKS=0');
      $query = $connection->getDatabasePlatform()->getTruncateTableSQL($metaData->getTableName());
      $connection->executeUpdate($query);
      $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }
  }
}