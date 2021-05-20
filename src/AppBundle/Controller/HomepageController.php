<?php

namespace AppBundle\Controller;

use Accurateweb\LocationBundle\Exception\LocationServiceException;
use Accurateweb\LocationBundle\Service\Location;
use AppBundle\Entity\Common\News;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\ContactUs\ContactUs;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\Number\Number;
use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Entity\Question\Question;
use AppBundle\Form\ContactUs\ContactUsType;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaint1stStepType;
use AppBundle\Model\InsuranceCompany\Branch\BranchRatingHelper;
use AppBundle\Repository\Geo\RegionRepository;
use AppBundle\Service\ContactUs\ContactUsMailer;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
  private $branchRatingHelper;
  private $regionRepository;
  private $locationService;
  private $contactUsMailer;

  public function __construct(
    BranchRatingHelper $branchRatingHelper,
    RegionRepository $regionRepository,
    Location $locationService,
    ContactUsMailer $contactUsMailer
  )
  {
    $this->branchRatingHelper = $branchRatingHelper;
    $this->regionRepository = $regionRepository;
    $this->locationService = $locationService;
    $this->contactUsMailer = $contactUsMailer;
  }

  /**
   * @Route("/", name="homepage")
   */
  public function indexAction()
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

    $omsChargeComplaint = new OmsChargeComplaint();
    $omsChargeComplaint->setRegion($region);

    $forms = [
      $this->createForm(OmsChargeComplaint1stStepType::class, $omsChargeComplaint, [
        // 'csrf_protection' => false,
      ]),
      $this->createForm(OmsChargeComplaint1stStepType::class, $omsChargeComplaint, [
        // 'csrf_protection' => false,
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
      'csrf_protection' => !$request->isXmlHttpRequest(),
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
        } catch (Exception $e)
        {
          $this->get('logger')->error('Failed to send contact us: ' . $e->getMessage());
        }

        if ($request->isXmlHttpRequest())
        {
          return new JsonResponse(1);
        }
        $this->addFlash('magnific', 'Ваше сообщение было успешно отправлено.');
        return $this->redirectToRoute('homepage');
      } else if ($request->isXmlHttpRequest())
      {
        $response = $this->render('@App/form/contact_us.html.twig', [
          'form' => $form->createView()
        ]);
        $response->setStatusCode(400);
        return $response;
      }
    }
    if ($request->isXmlHttpRequest())
    {
      $response = $this->render('@App/form/contact_us.html.twig', [
        'form' => $form->createView()
      ]);
      $response->setStatusCode(200);
      return $response;
    }

    return $this->render('@App/contact_us.html.twig', [
      'form' => $form->createView()
    ]);
  }

}
