<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Security;


use AppBundle\Entity\User\User;
use AppBundle\Exception\BitrixRequestException;
use AppBundle\Helper\DataFromBitrix;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
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

  protected $router;

  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  private $loadedUser;

  /**
   * BitrixAuthenticator constructor.
   * @param Security $security
   */
  public function __construct(
    Security $security,
    LoggerInterface $logger,
    RouterInterface $router,
    EntityManagerInterface $entityManager
  )
  {
    $this->security = $security;
    $this->logger = $logger;
    $this->router = $router;
    $this->entityManager = $entityManager;
  }

  public function supports(Request $request)
  {
      $dataFromBitrix = new DataFromBitrix($request);
      try {
          $this->loadedUser = $dataFromBitrix->getData('%s/ajax/authenticated_user.php');
          return true;
      } catch (BitrixRequestException $exception) {
          if (!($dataFromBitrix->getCode() === 401 && $dataFromBitrix->getParam('is_script'))) {
              $this->logger->error(sprintf('Error from Bitrix Authenticator: . %s', $exception->getMessage()));
          }
      }

      return false;
  }

  /**
   * @param Request $request
   * @param AuthenticationException|null $authException
   * @return Response
   */
  public function start(Request $request, AuthenticationException $authException = null)
  {
    // У нас пока нет своей страницы логина, поэтому придется сделать, как в Битриксе - отправить человека на главную страницу
    return new RedirectResponse('/');
  }

  /**
   * @param Request $request
   * @return mixed|null
   */
  public function getCredentials(Request $request)
  {
    // $this->loadedUser must have been set in supports
    // otherwise we should not be here
    return $this->loadedUser;
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

    $user = $this->entityManager->getRepository(User::class)
      ->findOneBy(['bitrixId' => $credentials['id']]);
    if (!$user)
    {
      /**
       * Если bitrixId еще не был добавлен для этого пользователя, то поиск по login
       */
      $user = $this->entityManager->getRepository(User::class)
        ->findOneBy(['login' => $credentials['login']]);
      if (!$user)
      {
        /**
         * Если это новый пользовтаель
         */
        $user = new User();
      }
    }
    /**
     * Всегда обновление данных для пользователя в момент авторизации
     */
    $user->setBitrixId($credentials['id']);
    $user->setLogin($credentials['login']);
    $user->setEmail($credentials['email']);
    $user->setFirstName($credentials['firstName']);
    $user->setLastName($credentials['lastName']);
    $user->setMiddleName($credentials['middleName']);
    $user->setRepresentative($credentials['representative']);
    $this->entityManager->persist($user);
    $this->entityManager->flush();

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
      return null;
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
    return null;
  }

  /**
   * @return bool
   */
  public function supportsRememberMe()
  {
    return true;
  }

}
