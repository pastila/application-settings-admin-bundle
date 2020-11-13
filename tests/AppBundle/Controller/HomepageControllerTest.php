<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Number\Number;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Fixtures\Question\Question;

class HomepageControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new Number());
    $this->addFixture(new Question());
  }

  public function testIndex()
  {
    $client = static::createClient();

    /**
     * Проверка, что страница открывается
     */
    $crawler = $client->request('GET', '/');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
  }

  /*** Проверка "Безбахил в цифрах":
  https://jira.accurateweb.ru/browse/BEZBAHIL-90
   **/
  public function testNumber()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    // Извлечение данных со страницы
    $numbersHtml = $crawler->filter('.b-info__item')->each(function (Crawler $node, $i)
    {
      return [
        'title' => $node->filter('.b-info__item-number')->text(),
        'description' => $node->filter('.b-info__item-text')->text(),
        'urlText' => trim($node->filter('.b-info__item-more a')->text()),
        'url' => $node->filter('.b-info__item-more a')->attr('href'),
      ];
    });
    $this->assertTrue(count($numbersHtml) > 1, 'Проверка, что данные вообще нашлись');

    // Проверка, что запись, с позицией 1 отобразиться первой:
    $this->assertEquals('999', $numbersHtml[0]['title'], 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Description', $numbersHtml[0]['description'], 'Проверка, что Описание совпадает');
    $this->assertEquals('Go', $numbersHtml[0]['urlText'], 'Проверка, что Текст кнопки совпадает');
    $this->assertEquals('http://bezbahil.ru/', $numbersHtml[0]['url'], 'Проверка, что Ссылка совпадает');

    $this->assertEquals('100 500', $numbersHtml[1]['title'], 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Example', $numbersHtml[1]['description'], 'Проверка, что Описание совпадает');
    $this->assertEquals('Go', $numbersHtml[1]['urlText'], 'Проверка, что Текст кнопки совпадает');
    $this->assertEquals('http://example.ru/', $numbersHtml[1]['url'], 'Проверка, что Ссылка совпадает');
  }

  /*** Проверка "Вопрос-ответ":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-89
   **/
  public function testQuestions()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    $questionsHtml = $crawler->filter('.b-question__item')->each(function (Crawler $node, $i)
    {
      return [
        'question' => trim($node->filter('.b-question__item-title')->text()),
        'answer' => trim($node->filter('.b-question__item-text')->text()),
      ];
    });
    $textPrev = 'Вопрос-ответ: ';
    $this->assertTrue(count($questionsHtml) > 0, $textPrev . 'Проверка, что данные вообще нашлись');
    $this->assertEquals('Пример вопроса', $questionsHtml[0]['question'], $textPrev . 'Проверка, что Вопрос совпадает');
    $this->assertEquals('Пример ответа', $questionsHtml[0]['answer'], $textPrev . 'Проверка, что Ответ совпадает');
  }
}