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
use AppBundle\Form\ContactUs\ContactUsType;
use AppBundle\Service\ContactUs\ContactUsMailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    ContactUsMailer $contactUsMailer,
    Location $locationService
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
      ->getQuery()
      ->getResult();

    $feedbacks = $em->getRepository(Feedback::class)
      ->getFeedbackActive()
      ->setMaxResults(6)
      ->orderBy('rv.createdAt', 'DESC')
      ->getQuery()
      ->getResult();

    $news = $em->getRepository(News::class)
      ->findNewsOrderByPublishedAt();

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

    // replace this example code with whatever you need
    return $this->render('@App/homepage.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
      'numbers' => $numbers,
      'questions' => $questions,
      'companyRating' => array_slice($this->branchRatingHelper->buildRating($region), 0, 5),
      'feedbacks' => $feedbacks,
      'news' => $news,
      'region' => $region,
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
    $data = $request->request->all();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid())
    {
      $em = $this->getDoctrine()->getManager();
      $em->persist($contactUs);
      $em->flush();

      try
      {
        $this->contactUsMailer->sendContactUs($contactUs);
      }
      catch (\Exception $e)
      {
        $this->get('logger')->error('Failed to send contact us: ' . $e->getMessage());
      }
      if ($request->isXmlHttpRequest())
      {
        return new JsonResponse(1);
      }
    }

    /**
     * Если пришел ajax запрос на создание формы
     */
    if ($request->isXmlHttpRequest() && empty($data))
    {
      $content = $this->render('@App/modal/contact_us.html.twig', [
        'form' => $form->createView()
      ]);

      $response = new Response();
      $response->setContent($content->getContent());
      $response->setStatusCode(Response::HTTP_OK);
      $response->headers->set('Content-Type', 'text/html');
      $response->headers->set('X-Requested-With', 'XMLHttpRequest');
      $response->send();
      die;
    }

    return $this->render('@App/contact_us.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
