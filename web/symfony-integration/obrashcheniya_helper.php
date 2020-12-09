<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
  die();
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/symfony-integration/config.php");

/**
 * Формирование URL для скачивания файла обращения в symfony
 * @param $path
 * @param $name
 * @return string
 */
function parsingPdfPath($name)
{
  $pos = strpos($name, 'PDF_');
  $str = mb_strcut($name, $pos);
  $str = substr_replace($str, ':', 17, 1);
  $str = substr_replace($str, ':', 20, 1);
  return $str;
}

/**
 * @param $array
 * @param $j
 * @return bool
 */
function findExistFile($array, $j)
{
  foreach ($array as $item)
  {
    if ($j == $item['image_number'])
    {
      return true;
    }
  }
  return false;
}

function getAppealFromSymfony($appeal_id, $login)
{
  try
  {
    $dbh = new \PDO('mysql:host=' . PERCONA_HOST . ';dbname=' . PERCONA_DATABASE, PERCONA_USER, PERCONA_PASSWORD);
    $sql = 'SELECT 
                sof.bitrix_id,
                sof.user_id,
                sof.image_number,
                su.login
                FROM s_obrashcheniya_files as sof
                JOIN s_users su ON su.id = sof.user_id AND su.login = :login
                WHERE sof.bitrix_id = :bitrix_id';
    $sth = $dbh->prepare($sql);
    $sth->execute(array('bitrix_id' => $appeal_id, 'login' => $login));
    return $sth->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e)
  {
    return [];
  }
}