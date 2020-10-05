<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Security;


use AppBundle\Entity\User\User;
use AppBundle\Exception\AuthenticatorRequestException;
use AppBundle\Exception\BitrixRequestException;
use AppBundle\Helper\DataFromBitrix;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class BitrixAuthenticator
 * @package AppBundle\Security
 */
class BitrixAuthenticator extends AbstractGuardAuthenticator
{
  /**
   * @var Security
   */
  private $security;

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * BitrixAuthenticator constructor.
   * @param Security $security
   */
  public function __construct(
    Security $security,
    LoggerInterface $logger
  )
  {
    $this->security = $security;
    $this->logger = $logger;
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
   * @return mixed|null
   * @throws AuthenticatorRequestException
   */
  public function getCredentials(Request $request)
  {
    $dataFromBitrix = new DataFromBitrix($request);
    try {
      $dataFromBitrix->getData('%s/ajax/authenticated_user.php');
      return $dataFromBitrix->getRes();
    } catch (BitrixRequestException $exception) {
      if (!($dataFromBitrix->getCode() === 401 && $dataFromBitrix->getParam('is_script'))) {
        $this->logger->error(sprintf('Error from Bitrix Authenticator: . %s', $exception->getMessage()));
      }
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
    if (null === $credentials) {
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
