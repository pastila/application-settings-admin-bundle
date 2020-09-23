<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Security;


use AppBundle\Entity\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class BitrixAuthenticator extends AbstractGuardAuthenticator
{
  private $security;

  public function __construct(Security $security)
  {
    $this->security = $security;
  }

  public function supports(Request $request)
  {
    //Нам надо проверять аутентификацию на каждый запрос
    return true;
  }

  /**
   * @param Request $request
   * @param AuthenticationException|null $authException
   * @return Response
   */
  public function start(Request $request, AuthenticationException $authException = null)
  {
    // TODO: Implement start() method.
  }

  /**
   * @param Request $request
   * @return mixed
   */
  public function getCredentials(Request $request)
  {
    $ch = curl_init(sprintf('%s/ajax/authenticated_user.php', 'http://nginx'));
    try
    {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-SF-SECRET: 2851f0ae-9dc7-4a22-9283-b86abfa44900',
        'X-SF-REMOTE-ADDR: ' . $request->getClientIp(),
        'X-Requested-With: XmlHttpRequest'
      ));

      curl_setopt($ch, CURLOPT_COOKIE, sprintf('BX_USER_ID=%s;BITRIX_SM_LOGIN=%s;BITRIX_SM_SOUND_LOGIN_PLAYED=%s;PHPSESSID=%s',
        $request->cookies->get('BX_USER_ID'),
        $request->cookies->get('BITRIX_SM_LOGIN'),
        $request->cookies->get('BITRIX_SM_SOUND_LOGIN_PLAYED'),
        $request->cookies->get('PHPSESSID')
      ));

      curl_setopt($ch, CURLOPT_VERBOSE, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $res = curl_exec($ch);
      $info = curl_getinfo($ch);
    } finally
    {
      curl_close($ch);
    }

    if ($info['http_code'] === 200)
    {
      return json_decode($res, true);
    }

    return null;
  }

  /**
   * @param $credentials
   * @param UserProviderInterface $userProvider
   * @return UserInterface|null
   */
  public function getUser($credentials, UserProviderInterface $userProvider)
  {
    if (null === $credentials)
    {
      // The token header was empty, authentication fails with HTTP Status
      // Code 401 "Unauthorized"
      return null;
    }

    $user = new User();

    $user->setId($credentials['id']);
    $user->setLogin($credentials['email']);
    $user->setFirstName($credentials['fullName']);
    $user->setIsAdmin($credentials['isAdmin']);

    return $user;
  }

  /**
   * @param $credentials
   * @param UserInterface $user
   * @return bool
   */
  public function checkCredentials($credentials, UserInterface $user)
  {
    return true;
  }

  /**
   * @param Request $request
   * @param AuthenticationException $exception
   * @return Response|null
   */
  public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
  {
    // TODO: Implement onAuthenticationFailure() method.
  }

  /**
   * @param Request $request
   * @param TokenInterface $token
   * @param string $providerKey
   * @return Response|null
   */
  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
  {
    // TODO: Implement onAuthenticationSuccess() method.
  }

  /**
   * @return bool
   */
  public function supportsRememberMe()
  {
    return true;
  }

}
