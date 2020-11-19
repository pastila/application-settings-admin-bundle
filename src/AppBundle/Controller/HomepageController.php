<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Common\News;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Number\Number;
use AppBundle\Entity\Question\Question;
use AppBundle\Helper\GetMessFromBitrix;
use AppBundle\Model\InsuranceCompany\Branch\BranchRatingHelper;
use AppBundle\Repository\Geo\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use AppBundle\Service\GeoLocation;
use Accurateweb\LocationBundle\LocationResolver\GeoLocationResolver;

class HomepageController extends Controller
{
  private $branchRatingHelper;
  private $settingManager;
  private $regionRepository;
  private $geoLocationResolver;

  public function __construct(
    BranchRatingHelper $branchRatingHelper,
    SettingManagerInterface $settingManager,
    RegionRepository $regionRepository,
    GeoLocation $geoLocationResolver
  )
  {
    $this->branchRatingHelper = $branchRatingHelper;
    $this->settingManager = $settingManager;
    $this->regionRepository = $regionRepository;
    $this->geoLocationResolver = $geoLocationResolver;
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
    $region = $this->geoLocationResolver->getRegion();

    // replace this example code with whatever you need
    return $this->render('@App/homepage.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
      'numbers' => $numbers,
      'questions' => $questions,
      'companyRating' => array_slice($this->branchRatingHelper->buildRating(), 0, 5),
      'region' => null,
      'feedbacks' => $feedbacks,
      'news' => $news,
      'region' => $region,
    ]);
  }
}
