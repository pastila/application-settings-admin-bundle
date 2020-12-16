<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Util;


use AppBundle\Exception\BitrixHelperException;
use Doctrine\DBAL\Driver\PDOConnection;
use http\Exception\InvalidArgumentException;
use Doctrine\DBAL\Driver\Connection;

class BitrixHelper
{
  private $con;

  public function __construct(Connection $connection)
  {
    $this->con = $connection;
  }

  public function updatePropertyElementValue($IBLOCK_ID, $IBLOCK_ELEMENT_ID, $CODE, $VALUE, $VALUE_ENUM, $VALUE_NUM)
  {
    $stmt = $this->con->prepare('
SELECT ID 
FROM b_iblock_property
WHERE IBLOCK_ID=:iblock_id AND `CODE`=:code');

    $stmt->bindValue(':iblock_id', $IBLOCK_ID);
    $stmt->bindValue(':code', $CODE);

    $stmt->execute();

    $iblockPropertyId = $stmt->fetchColumn();
    $stmt->closeCursor();

    if (!$iblockPropertyId)
    {
      throw new InvalidArgumentException(sprintf('iblock property %s for iblock %s not found', $CODE, $IBLOCK_ID));
    }

    $stmt = $this->con->prepare('
SELECT VALUE, VALUE_ENUM
FROM b_iblock_element_property
WHERE  IBLOCK_PROPERTY_ID = :iblock_property_id AND IBLOCK_ELEMENT_ID = :iblock_element_id');

    $stmt->bindValue(':iblock_property_id', $iblockPropertyId);
    $stmt->bindValue(':iblock_element_id', $IBLOCK_ELEMENT_ID);

    $stmt->execute();

    $row = $stmt->fetch();

    $stmt->closeCursor();

    if ($row)
    {
      # Если есть, то
      $stmt = $this->con->prepare('
UPDATE b_iblock_element_property
SET `VALUE` = :value, `VALUE_ENUM` = :value
WHERE IBLOCK_PROPERTY_ID = :iblock_property_id AND IBLOCK_ELEMENT_ID = :iblock_element_id');
    }
    else
    {
# Если такого нет, то
      $stmt = $this->con->prepare('
INSERT INTO b_iblock_element_property (IBLOCK_PROPERTY_ID, IBLOCK_ELEMENT_ID, VALUE, VALUE_ENUM, VALUE_NUM, DESCRIPTION)
VALUES (:iblock_property_id, :iblock_element_id, :value, :value_enum, :value_num, NULL)');
    }

    $stmt->bindValue(':iblock_property_id', $iblockPropertyId);
    $stmt->bindValue(':iblock_element_id', $IBLOCK_ELEMENT_ID);
    $stmt->bindValue(':value', $VALUE);
    $stmt->bindValue(':value_enum', $VALUE_ENUM);
    $stmt->bindValue(':value_num', $VALUE_NUM);

    $result = $stmt->execute();

    if (false === $result)
    {
      throw new BitrixHelperException('Unable to update property value. PDO error: '.json_encode($this->con->errorInfo()));
    }
  }
}
