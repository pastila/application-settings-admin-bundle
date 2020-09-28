<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Company\Feedback;
use AppBundle\Repository\Company\CompanyRepository;
use AppBundle\Repository\Company\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
  private $feedbackRepository;

  public function __construct(FeedbackRepository $feedbackRepository)
  {
    $this->feedbackRepository = $feedbackRepository;
  }
  public function _userPanelAction(Request $request)
  {
    $ch = curl_init(sprintf('%s/ajax/get_obrashcheniya.php', 'http://nginx'));
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
      $res = json_decode($res, true);

      $nbAppeals = isset($res['nbAppeals']) ? $res['nbAppeals'] : null;
      $nbSent = isset($res['nbSent']) ? $res['nbSent'] : null;
    }
    else
    {
      $nbAppeals = null;
      $nbSent = null;
    }

    $nbReviews = $this->feedbackRepository
      ->countUserReviews($this->get('security.token_storage')->getToken()->getUser());

    return $this->render('User/_panel.html.twig', [
      'nbReviews' => $nbReviews,
      'nbAppeals' => $nbAppeals,
      'nbSent' => $nbSent,
    ]);
  }
}