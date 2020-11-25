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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;

class HomepageController extends Controller
{
  private $branchRatingHelper;
  private $settingManager;
  private $regionRepository;
  private $locationService;

  public function __construct(
    BranchRatingHelper $branchRatingHelper,
    SettingManagerInterface $settingManager,
    RegionRepository $regionRepository,
    Location $locationService
  )
  {
    $this->branchRatingHelper = $branchRatingHelper;
    $this->settingManager = $settingManager;
    $this->regionRepository = $regionRepository;
    $this->locationService = $locationService;
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
      'companyRating' => array_slice($this->branchRatingHelper->buildRating(), 0, 5),
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
    $data = $request->request->all();
    $data = ['id' => 3];

    /**
     * @var Region $region
     */
    $region = $this->getDoctrine()->getManager()->getRepository(Region::class)
      ->findOneBy(['id' => $data['id']]);

    $resolvedLocation = $region->getResolvedLocation();
    $this->locationService->setResolvedLocation($resolvedLocation);
  }
}
