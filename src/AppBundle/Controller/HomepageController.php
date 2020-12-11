<?php

namespace AppBundle\Controller;

use Accurateweb\LocationBundle\Exception\LocationServiceException;
use Accurateweb\LocationBundle\Service\Location;
use AppBundle\Entity\Common\News;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\Number\Number;
use AppBundle\Entity\Question\Question;
use AppBundle\Model\InsuranceCompany\Branch\BranchRatingHelper;
use AppBundle\Repository\Geo\RegionRepository;
use AppBundle\Form\Obrashcheniya\ObrashcheniyaType;
use AppBundle\Helper\Year\Year;
use AppBundle\Model\Obrashcheniya\Obrashcheniya;
use AppBundle\Form\ContactUs\ContactUsType;
use AppBundle\Service\ContactUs\ContactUsMailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ContactUs\ContactUs;

class HomepageController extends Controller
{
  private $branchRatingHelper;
  private $settingManager;
  private $regionRepository;
  private $locationService;
  private $contactUsMailer;

  public function __construct(
    BranchRatingHelper $branchRatingHelper,
    SettingManagerInterface $settingManager,
    RegionRepository $regionRepository,
    Location $locationService,
    ContactUsMailer $contactUsMailer
  )
  {
    $this->branchRatingHelper = $branchRatingHelper;
    $this->settingManager = $settingManager;
    $this->regionRepository = $regionRepository;
    $this->locationService = $locationService;
    $this->contactUsMailer = $contactUsMailer;
  }

  /**
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $numbers = $em->getRepository(Number::class)
      ->getAll()
      ->getQuery()
      ->getResult();

    $questions = $em->getRepository(Question::class)
      ->getQueryAllSortByPosition()
      ->setMaxResults(3)
      ->getQuery()
      ->getResult();

    $feedbacks = $em->getRepository(Feedback::class)
      ->getFeedbackActive()
      ->setMaxResults(6)
      ->orderBy('rv.createdAt', 'DESC')
      ->getQuery()
      ->getResult();

    $news = $em->getRepository(News::class)
      ->findNewsOrderByPublishedAt(6);

    /**
     * Получение региона по IP клиента
     */
    try
    {
      $region = $this->locationService->getLocation();
    } catch (LocationServiceException $e)
    {
      $this->get('logger')->err('Unable to determine user region.');
      throw $e;
    }

    $regions = $this->regionRepository
      ->createQueryBuilder('r')
      ->orderBy('r.name')
      ->getQuery()
      ->getResult();

    $obrashcheniya = new Obrashcheniya();
    $obrashcheniya->setRegion($region);
    $forms = [
      $this->createForm(ObrashcheniyaType::class, $obrashcheniya, [
        'csrf_protection' => false,
        'data' => $obrashcheniya
      ]),
      $this->createForm(ObrashcheniyaType::class, $obrashcheniya, [
        'csrf_protection' => false,
        'data' => $obrashcheniya
      ]),
    ];
    $formViews = [];
    foreach ($forms as $form)
    {
      $formViews[] = $form->createView();
    }

    return $this->render('@App/homepage.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
      'numbers' => $numbers,
      'questions' => $questions,
      'questions_count' => $em->getRepository(Question::class)->getCount(),
      'companyRating' => array_slice($this->branchRatingHelper->buildRating($region), 0, 5),
      'feedbacks' => $feedbacks,
      'news' => $news,
      'region' => $region,
      'forms' => $formViews,
      'regions' => $regions
    ]);
  }

  /**
   * @Route(path="/select-location", name="homepage_select_location")
   * @param Request $request
   */
  public function selectLocationAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $repository = $em->getRepository(Region::class);

    /**
     * @var Region $region
     */
    $region = $repository->findOneBy(['id' => $request->get('region_id')]);
    if (!$region)
    {
      return new JsonResponse([
        'Not found region: ' . $request->get('region_id')
      ], 400);
    }

    $resolvedLocation = $region->getResolvedLocation();
    $this->locationService->setResolvedLocation($resolvedLocation);

    return new JsonResponse(1);
  }

  /**
   * @Route("/contact_us", name="contact_us")
   * @param Request $request
   * @return Response
   */
  public function contactUsAction(Request $request)
  {
    $contactUs = new ContactUs();
    $contactUs->setAuthor($this->getUser());
    $form = $this->createForm(ContactUsType::class, $contactUs, [
      'csrf_protection' => false,
    ]);

    $form->handleRequest($request);
    if ($form->isSubmitted())
    {
      if ($form->isValid())
      {
        $em = $this->getDoctrine()->getManager();
        $em->persist($contactUs);
        $em->flush();

        try
        {
          $this->contactUsMailer->sendContactUs($contactUs);
        } catch (\Exception $e)
        {
          $this->get('logger')->error('Failed to send contact us: ' . $e->getMessage());
        }

        if ($request->isXmlHttpRequest())
        {
          return new JsonResponse(1);
        }
        $this->addFlash('magnific', 'Спасибо, сообщение было отправлено');
        return $this->redirectToRoute('homepage');
      } else if ($request->isXmlHttpRequest())
      {
        $response = $this->render('@App/modal/contact_us.html.twig', [
          'form' => $form->createView()
        ]);
        $response->setStatusCode(400);
        return $response;
      }
    }
    if ($request->isXmlHttpRequest())
    {
      $response = $this->render('@App/modal/contact_us.html.twig', [
        'form' => $form->createView()
      ]);
      $response->setStatusCode(200);
      return $response;
    }

    return $this->render('@App/contact_us.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/homepage-obrashcheniya", name="homepage_obrashcheniya")
   */
  public function obrashcheniyaAction(Request $request)
  {
    $obrashcheniya = $request->request->get('obrashcheniya');
    if (empty($obrashcheniya) || !key_exists('year', $obrashcheniya) || !key_exists('region', $obrashcheniya))
    {
      throw new NotFoundHttpException(sprintf('Year in obrashcheniya not found'));
    }
    $em = $this->getDoctrine()->getManager();
    $region = $em->getRepository(Region::class)
      ->find($obrashcheniya['region']);

    $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
    $url = sprintf($baseurl . '/forma-obrashenija/?year=%s&region=%s',
      Year::getYear($obrashcheniya['year']),
      !empty($region) ? $region->getBitrixCityHospitalId() : null);

    return $this->redirect($url, 301);
  }
}
