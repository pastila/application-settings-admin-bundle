<?php

namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Company\Citation;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Fixtures\Geo\Region;

class FeedbackControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new Citation());
    $this->addFixture(new Region());
  }

  /**
   * Тест успешного открытие страниц,
   * без дополительных проверок, таких как проверка модерации
   * https://jira.accurateweb.ru/browse/BEZBAHIL-80
   */
  public function testOpenPage()
  {
    $client = static::createClient();

    $client->request('GET', '/feedback');
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что страница со списком отзыовм открывается');

    $client->request('GET', '/companies/' . $this->getReference('sogaz-med')->getSlug() . '/feedback');
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что страница с фильтрацией по компании открылась');

    $client->request('GET', '/cabinet/feedback');
    $this->assertEquals(302, $client->getResponse()->getStatusCode(), 'Проверка, что кабинет с отзывами не открывается, если не авторизован');

    $client->request('GET', '/feedback/add');
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что страница создания отзыва открывается');

    $client->request('GET', '/feedback/' . $this->getReference('feedback-simple')->getId());
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что страница отзыва открывается');
  }

  /**
   * Тест проверки доступности открытия страницы с одним отзывом,
   * с проверкой на модерацию и комрании/филиалы
   * https://jira.accurateweb.ru/browse/BEZBAHIL-80
   */
  public function testShowAccess()
  {
    $client = static::createClient();

    $client->request('GET', '/feedback/9954395743957439');
    $this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Открытие страницы отзыва, которого нет');

    $client->request('GET', '/feedback/' . $this->getReference('feedback-moderation-not')->getId());
    $this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Открытие отзыва не прошедшего модерацию');

    $client->request('GET', '/feedback/' . $this->getReference('feedback-moderation-rejected')->getId());
    $this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Открытие отклоненнго отзыва');

    $client->request('GET', '/feedback/' . $this->getReference('feedback-branch-not')->getId());
    $this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Открытие отзыва с неактивным филиалом');

    $client->request('GET', '/feedback/' . $this->getReference('feedback-company-not')->getId());
    $this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Открытие отзыва с неактивной компанией');
  }

  /**
   * Тест на отображение списка регионов и филиалов при создание отзыва
   * https://jira.accurateweb.ru/browse/BEZBAHIL-80
   */
  public function testNewAjaxFeedback()
  {
    $client = static::createClient();

    /*** ПРОВЕРКА РЕГИНОВ: */
    $crawler = $client->request('POST', '/feedback/region-search', [
      'name_city' => $this->getReference('region-66')->getName()
    ], [],
      [
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
      ]);
    $regionsHtml = $crawler->filter('.custom-serach__items_item')->each(function (Crawler $node, $i)
    {
      return [
        'name' => trim($node->text()),
        'value' => $node->attr('value'),
      ];
    });
    $this->assertEquals(count($regionsHtml), 1, 'Проверка сопадения кол-ва регинов');
    $this->assertEquals($regionsHtml[0]['name'], "66 Свердловская область", 'Проверка данных в списке по регинам');
    $this->assertEquals($regionsHtml[0]['value'], "2", 'Проверка данных в списке по регинам');

    /*** ПРОВЕРКА ФИЛИАЛОВ: */
    /**
     * Запрос и извлечение данных по филиалам по выбранному региону
     */
    $crawler = $client->request('POST', '/feedback/company-search', [
      'region_id' => $this->getReference('region-66')->getId()
    ], [],
      [
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
      ]);
    $citiesHtml = $crawler->filter('.custom-serach__items_item')->each(function (Crawler $node, $i)
    {
      return [
        'name' => trim($node->text()),
        'value' => $node->attr('value'),
      ];
    });
    $this->assertEquals(count($citiesHtml), 3, 'Проверка сопадения кол-ва филиалов');
    $this->assertEquals($citiesHtml[0]['name'], "СОГАЗ-МЕД", 'Проверка данных в списке по филиалам');
    $this->assertEquals($citiesHtml[0]['value'], "1", 'Проверка данных в списке по филиалам');
    $this->assertEquals($citiesHtml[1]['name'], "ИНГОССТРАХ-М", 'Проверка данных в списке по филиалам');
    $this->assertEquals($citiesHtml[1]['value'], "3", 'Проверка данных в списке по филиалам');
  }
}