<?php


namespace Tests\AppBundle\Controller;


use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\Company\Company;
use Tests\AppBundle\Fixtures\News\News;
use Tests\AppBundle\Fixtures\Company\Feedback;
use Tests\AppBundle\Fixtures\Number\Number;
use Tests\AppBundle\Fixtures\Menu\MenuFooter;
use Tests\AppBundle\Fixtures\Menu\MenuHeader;
use Symfony\Component\DomCrawler\Crawler;
use Tests\AppBundle\Fixtures\Question\Question;
use Tests\AppBundle\Fixtures\Setting\Setting;

class HomepageControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new Number());
    $this->addFixture(new MenuFooter());
    $this->addFixture(new MenuHeader());
    $this->addFixture(new Question());
    $this->addFixture(new Feedback());
    $this->addFixture(new News());
    $this->addFixture(new Setting());
  }

  public function testIndex()
  {
    $client = static::createClient();

    $client->request('GET', '/');
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что главная страница открывается');
    $client->request('GET', '/contact_us');
    $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что страница "Написать нам" открывается');
  }

  /*** Проверка "Безбахил в цифрах":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-90
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

  /*** Проверка "Слайдера отзывов":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-88
   **/
  public function testSlider()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    // Извлечение данных со страницы
    $feedbacksHtml = $crawler->filter('.b-reviews__item')->each(function (Crawler $node, $i)
    {
      return [
        'valuation' => $node->filter('.svg-icon--star.active')->count(),
        'text' => trim($node->filter('.b-reviews__item-text')->text()),
        'author' => trim($node->filter('.b-reviews__item-user .b-reviews__item-name')->text()),
        'date' => trim($node->filter('.b-reviews__item-user .b-reviews__item-date')->text()),
      ];
    });
    $this->assertTrue(count($feedbacksHtml) > 1, 'Проверка, что данные вообще нашлись');
    $this->assertEquals(4, $feedbacksHtml[0]['valuation'], 'Проверка, что Оценка совпадает');
    $this->assertEquals('Привет Мир!', $feedbacksHtml[0]['text'], 'Проверка, что Текст совпадает');
    $this->assertEquals('Армстронг', $feedbacksHtml[0]['author'], 'Проверка, что Автор совпадает');
    $this->assertEquals('02 января, 2020', $feedbacksHtml[0]['date'], 'Проверка, что Дата совпадает');
    $this->assertEquals(4, $feedbacksHtml[1]['valuation'], 'Проверка, что Оценка совпадает');
    $this->assertEquals('Foo', $feedbacksHtml[1]['text'], 'Проверка, что Текст совпадает');
    $this->assertEquals('From fixtures', $feedbacksHtml[1]['author'], 'Проверка, что Автор совпадает');
    $this->assertEquals('01 января, 2020', $feedbacksHtml[1]['date'], 'Проверка, что Дата совпадает');
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



  /*** Проверка "Меню Header":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-92
   **/
  public function testMenuHeader()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    // Извлечение данных со страницы
    $headerHtml = $crawler->filter('.app-header__in .app-header__list li a')->each(function (Crawler $node, $i)
    {
      return [
        'title' => trim($node->text()),
        'url' => $node->attr('href'),
      ];
    });
    $textPrev = 'Меню Header: ';
    $this->assertTrue(count($headerHtml) > 1, $textPrev . 'Проверка, что данные вообще нашлись');
    $this->assertEquals('меню_шапка_1', $headerHtml[0]['title'], $textPrev . 'Проверка, что Заголовок меню совпадает');
    $this->assertEquals('http://bezbahil.ru/1', $headerHtml[0]['url'], $textPrev . 'Проверка, что URL меню совпадает');
    $this->assertEquals('меню_шапка_2', $headerHtml[1]['title'], $textPrev . 'Проверка, что Заголовок моб.меню совпадает');
    $this->assertEquals('http://bezbahil.ru/2', $headerHtml[1]['url'], $textPrev . 'Проверка, что URL моб.меню совпадает');
  }

  /*** Проверка "Меню Header Mobile":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-92
   **/
  public function testMenuHeaderMobile()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    // Извлечение данных со страницы
    $headerHtml = $crawler->filter('.nav-mobile__list li a')->each(function (Crawler $node, $i)
    {
      return [
        'title' => trim($node->text()),
        'url' => $node->attr('href'),
      ];
    });
    $textPrev = 'Меню Header Mobile: ';
    $this->assertTrue(count($headerHtml) > 1, $textPrev . 'Проверка, что данные вообще нашлись');
    $this->assertEquals('меню_шапка_1', $headerHtml[0]['title'], $textPrev . 'Проверка, что Заголовок моб.меню совпадает');
    $this->assertEquals('http://bezbahil.ru/1', $headerHtml[0]['url'], $textPrev . 'Проверка, что URL моб.меню совпадает');
    $this->assertEquals('меню_шапка_2', $headerHtml[1]['title'], $textPrev . 'Проверка, что Заголовок моб.меню совпадает');
    $this->assertEquals('http://bezbahil.ru/2', $headerHtml[1]['url'], $textPrev . 'Проверка, что URL моб.меню совпадает');
  }

  /*** Проверка "Меню Footer":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-92
   **/
  public function testMenuFooter()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    // Извлечение данных со страницы
    $headerHtml = $crawler->filter('.app-footer__list li a')->each(function (Crawler $node, $i)
    {
      return [
        'title' => trim($node->text()),
        'url' => $node->attr('href'),
      ];
    });
    $textPrev = 'Меню Footer: ';
    $this->assertTrue(count($headerHtml) > 1, $textPrev . 'Проверка, что данные вообще нашлись');
    $this->assertEquals('меню_футер_1', $headerHtml[0]['title'], $textPrev . 'Проверка, что Заголовок меню совпадает');
    $this->assertEquals('http://bezbahil.ru/1', $headerHtml[0]['url'], $textPrev . 'Проверка, что URL меню совпадает');
    $this->assertEquals('меню_футер_2', $headerHtml[1]['title'], $textPrev . 'Проверка, что Заголовок меню совпадает');
    $this->assertEquals('http://bezbahil.ru/2', $headerHtml[1]['url'], $textPrev . 'Проверка, что URL меню совпадает');
  }

  /*** Проверка "Меню Footer":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-92
   **/
  public function testMenuSocial()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    $socialHtml = $crawler->filter('.app-footer__soc a')->each(function (Crawler $node, $i)
    {
      return [
        'url' => $node->attr('href'),
      ];
    });
    $textPrev = 'Меню Social: ';
    $this->assertTrue(count($socialHtml) > 1, $textPrev . 'Проверка, что данные вообще нашлись');
    $this->assertEquals('#social_telegram', $socialHtml[0]['url'], $textPrev . 'Проверка, что ссылка на telegram верная');
    $this->assertEquals('#social_instagram', $socialHtml[1]['url'], $textPrev . 'Проверка, что ссылка на instagram верная');
  }

  /*** Проверка "Меню c пользовательским соглашением и политикой по обработке персональных данных":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-92
   **/
  public function testMenuAgreementAndPersonal()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    $this->assertEquals('#agreement', $crawler->filter('.app-footer__agree a')->attr('href'), 'Проверка, что Адрес страницы с пользовательским соглашением совпадает');
    $this->assertEquals('#personal_data', $crawler->filter('.app-footer__personal a')->attr('href'), 'Проверка, что сАдрес страницы с политикой по обработке персональных данных совпадает');
  }

  /*** Проверка "Топ страховых компаний":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-87
   **/
  public function testCompanyRating()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    // Извлечение данных со страницы
    $companyTopsHtml = $crawler->filter('.b-rating__block .b-rating__item')->each(function (Crawler $node, $i)
    {
      return [
        'name' => $node->filter('.b-rating__item-title')->text(),
        'rating' => $node->filter('.svg-icon--star.active')->count(),
      ];
    });
    $textPrev = 'Топ страховых компаний: ';
    $this->assertTrue(count($companyTopsHtml) > 0, $textPrev . 'Проверка, что данные вообще нашлись');
    $this->assertEquals('ИНГОССТРАХ-М', $companyTopsHtml[0]['name'], $textPrev . 'Проверка, что Компания совпадает');
    $this->assertEquals(5, $companyTopsHtml[0]['rating'], $textPrev . 'Проверка, что Рейтинг совпадает');
    $this->assertEquals('СОГАЗ-МЕД', $companyTopsHtml[1]['name'], $textPrev . 'Проверка, что Компания совпадает');
    $this->assertEquals(4, $companyTopsHtml[1]['rating'], $textPrev . 'Проверка, что Рейтинг совпадает');
    $this->assertEquals('АКБАРС-МЕД', $companyTopsHtml[2]['name'], $textPrev . 'Проверка, что Компания совпадает');
    $this->assertEquals(3, $companyTopsHtml[2]['rating'], $textPrev . 'Проверка, что Рейтинг совпадает');
  }

  /*** Проверка "Блог, медицинский инсайдер":
   * https://jira.accurateweb.ru/browse/BEZBAHIL-91
   **/
  public function testNews()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    // Извлечение данных со страницы
    $newsHtml = $crawler->filter('.b-insider__item-content')->each(function (Crawler $node, $i)
    {
      return [
        'title' => trim($node->filter('.b-insider__item-title')->text()),
        'announce' => trim($node->filter('.b-insider__item-text')->text()),
      ];
    });

    $textPrev = 'Блог: ';
    $this->assertTrue(count($newsHtml) > 1, $textPrev . 'Проверка, что данные вообще нашлись');
    $this->assertEquals('Заголовок1', $newsHtml[0]['title'], $textPrev . 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Анонс статьи1', $newsHtml[0]['announce'], $textPrev . 'Проверка, что Анонс совпадает');
    $this->assertEquals('Заголовок2', $newsHtml[1]['title'], $textPrev . 'Проверка, что Заголовок совпадает');
    $this->assertEquals('Анонс статьи2', $newsHtml[1]['announce'], $textPrev . 'Проверка, что Анонс совпадает');
  }

  /*** Проверка страницы "Форма Написать нам:
   * https://jira.accurateweb.ru/browse/BEZBAHIL-97
   */
  public function testContactUs()
  {
    $client = static::createClient();

    $client->request('POST', '/contact_us', [
      'contact_us' => [
        "author_name" => 'Фон Браун',
        "email" => 'brayn@mail.com',
        "message" => 'Как отправить обращение?'
      ]
    ], [],
      [
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
      ]);

    /*
     * Проверка, что форма "Написать нам" была сохранена в базе
     */
    $contactUs = $this->getEntityManager()
      ->getRepository(\AppBundle\Entity\ContactUs\ContactUs::class)
      ->createQueryBuilder('cu')
      ->andWhere('cu.authorName = :author_name')
      ->setParameters([
        'author_name' => 'Фон Браун',
      ])
      ->getQuery()
      ->getResult();
    $this->assertTrue(!empty($contactUs));
  }
}