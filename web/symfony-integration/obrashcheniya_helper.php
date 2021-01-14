<?php

use Bitrix\Main\Application;

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

/**
 * @param $appeal_id
 * @param $login
 * @return array
 */
function getAppealFromSymfony($appeal_id, $login)
{
  try
  {
    $dbh = new \PDO('mysql:host=' . PERCONA_HOST . ';dbname=' . PERCONA_DATABASE, PERCONA_USER, PERCONA_PASSWORD);
    $sql = 'SELECT 
                sof.bitrix_id,
                sof.user_id,
                sof.image_number,
                sof.file,
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

/**
 * Удаление записи о файле из таблицы файлов обращений
 * @param $appeal_id
 * @param $image_number
 * @param $login
 * @return bool
 */
function deleteAppealFileFromSymfony($appeal_id, $image_number)
{
  try
  {
    $con = Application::getConnection();
    $con->queryExecute('DELETE FROM s_obrashcheniya_files
            WHERE bitrix_id = ' . $appeal_id . ' AND image_number = ' . $image_number);

    return true;
  } catch (PDOException $e)
  {
    return false;
  }
}

/**
 * Получение кол-ва изображений в обращении на основе массива, полученнного из базы
 * @param $array
 * @return int
 */
function countExistFile($array)
{
  $count = 0;
  foreach ($array as $item)
  {
    if ($item['image_number'] !== null)
    {
      $count++;
    }
  }

  return $count;
}

/**
 * Поиск в массиве, полученного из базы записи о сформированном изображении
 * @param $array
 * @return int
 */
function existAppeal($array)
{
  foreach ($array as $item)
  {
    /**
     * image_number === null для обращений, иначе это прикреп.изображение
     */
    if ($item['image_number'] === null)
    {
      return true;
    }
  }

  return false;
}

/**
 * Проверка, что файл pdf
 * @param $array
 * @param $j
 * @return bool
 */
function fileIsPdf($array, $j)
{
  foreach ($array as $item)
  {
    if ($j == $item['image_number'])
    {
      return getTypeByExt($item['file']) === 'pdf';
    }
  }
  return false;
}

/**
 * @param $name
 * @return mixed|null
 */
function getTypeByExt($name)
{
  $array = explode(".", $name);
  return (is_array($array) && count($array) > 1) ? end($array) : null;
}

/**
 * Отправка обращения в symfony
 * @param $url
 * @param $token
 * @param $data
 * @throws ErrorException
 */
function sendAppealToSymfony($url, $token, $data)
{
  $url = sprintf($url, $token, $data);
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $res = curl_exec($ch);
  $info = curl_getinfo($ch);
  $code = key_exists('http_code', $info) ? $info['http_code'] : null;

  if ($code !== 200) {
    throw new ErrorException('it is impossible to send curl an appeal to symfony, code: '. $code);
  }
}