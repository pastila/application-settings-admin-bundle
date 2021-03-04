<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequestManager;
use Tests\AppBundle\AppWebTestCase;
use Tests\AppBundle\Fixtures\User\User;

class RegistrationControllerTest extends AppWebTestCase
{
  protected function setUpFixtures()
  {
    $this->addFixture(new User());
  }

  /**
   * Тест создания кода и отправки его по смс
   * https://jira.accurateweb.ru/browse/BEZBAHIL-183
   */
  public function testGenerateSms()
  {
    $client = $this->client;
    $phoneVerificationRequestManager = $this->getMockBuilder(PhoneVerificationRequestManager::class)
      ->disableOriginalConstructor()
      ->setMethods(['sendVerificationCode', 'persist'])
      ->getMock();
    $phoneVerificationRequestManager->method('sendVerificationCode')->willReturn(true);
    $phoneVerificationRequestManager->method('persist');
    $client->getContainer()->set(PhoneVerificationRequestManager::class, $phoneVerificationRequestManager);

    $phone = urlencode('+7(912)235-57-17');
    $client->request('GET', sprintf('/register/generate-sms-code?phone=%s', $phone));$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Проверка, что пользователь получит на странице сообщение об успешно отправленной смс, 
    при условии mock функций отправки смс');

    $client->request('GET', '/register/generate-sms-code');
    $this->assertEquals(422, $client->getResponse()->getStatusCode(), 'Проверка, что телефон должен быть заполнен');

    $client->request('GET', sprintf('/register/generate-sms-code?phone=%s', '89122355717'));
    $this->assertEquals(422, $client->getResponse()->getStatusCode(), 'Проверка, что сработает валидация при неправильном формат телефона');

    $phone = $this->getReference('user-sogaz-med-66')->getPhone();
    $phone = urlencode($phone);
    $client->request('GET', sprintf('/register/generate-sms-code?phone=%s', $phone));
    $this->assertEquals(406, $client->getResponse()->getStatusCode(), 'Проверка, что пользователь с таким телефоном уже существует');
  }
}
