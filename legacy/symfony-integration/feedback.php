<?php

exit('Bitrix no more.');

//if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
//{
//  die();
//}
//
///**
// * Получение кол-ва отзывов из symfony
// *
// * @param $login
// * @param $token
// * @return int
// */
//function getCountReviews($login, $token)
//{
//  $url = sprintf('http://nginx/api/v1/panel?user=%s&api_token=%s', $login, $token);
//  $ch = curl_init($url);
//
//  curl_setopt($ch, CURLOPT_VERBOSE, true);
//  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//  $res = curl_exec($ch);
//  $info = curl_getinfo($ch);
//  $code = key_exists('http_code', $info) ? $info['http_code'] : null;
//
//  $nbReviews = 0;
//  if ($code === 200)
//  {
//    $data = json_decode($res, true);
//    $nbReviews = !empty($data['nbReviews']) ? $data['nbReviews'] : 0;
//  }
//
//  return $nbReviews;
//}
