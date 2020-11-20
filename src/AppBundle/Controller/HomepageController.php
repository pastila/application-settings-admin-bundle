<?php

namespace AppBundle\Controller;

use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use AppBundle\Entity\Number\Number;
use AppBundle\Entity\Question\Question;
use AppBundle\Helper\GetMessFromBitrix;
use AppBundle\Model\InsuranceCompany\Branch\BranchRatingHelper;
use AppBundle\Repository\Geo\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
  private $branchRatingHelper;
  private $settingManager;
  private $regionRepository;

  public function __construct(
    BranchRatingHelper $branchRatingHelper,
    SettingManagerInterface $settingManager,
    RegionRepository $regionRepository
  )
  {
    $this->branchRatingHelper = $branchRatingHelper;
    $this->settingManager = $settingManager;
    $this->regionRepository = $regionRepository;
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

    $region = null;
    if (false)
    {
      /**
       * Поиск региона TODO: реализуется в след.задачах
       */
    } else
    {
      /**
       * Если регион не был получен, то берем выбранный по умолчанию
       */
      $regionId = $this->settingManager->getValue('region_default');
      if ($regionId)
      {
        $region = $this->regionRepository->findOneBy(['id' => $regionId]);
      }
    }

    // replace this example code with whatever you need
    return $this->render('@App/homepage.html.twig', [
      'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
      'numbers' => $numbers,
      'questions' => $questions,
      'companyRating' => array_slice($this->branchRatingHelper->buildRating($region), 0, 5),
      'region' => $region
    ]);
  }
}
