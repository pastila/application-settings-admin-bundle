<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller\InsuranceCompany;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use AppBundle\Form\InsuranceCompany\FeedbackListFilterType;
use AppBundle\Model\InsuranceCompany\Branch\BranchRatingHelper;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Model\InsuranceCompany\FeedbackListFilterUrlBuilder;
use AppBundle\Model\Pagination;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends Controller
{
  private $branchRatingHelper;

  public function __construct(BranchRatingHelper $branchRatingHelper)
  {
    $this->branchRatingHelper = $branchRatingHelper;
  }

  /**
   * @Route(path="/reviews")
   */
  public function indexAction(Request $request)
  {
//    if ($_GET)
//    {
//      $get_letter = $_GET;
//      if (isset($get_letter["letter"]))
//      {
//        setcookie("letter", "yes", 0);
//      }
//    }
//    $asset = Asset::getInstance();
//
//    $url = $APPLICATION->GetCurDir();
//
//
//    CModule::IncludeModule("iblock");
//    global $USER;
//
//    $sort_url = $_GET;
//
//    $array_all_company = array();

    /**
     * Получает список компаний и их КПП
     * SELECT t.ID, t.IBLOCK_ID, t.NAME, t.DATE_ACTIVE_FROM, prop.VALUE as KPP, t.CODE
     * FROM b_iblock_element t
     * LEFT JOIN b_iblock_element_property prop on prop.IBLOCK_ELEMENT_ID = t.ID AND prop.IBLOCK_PROPERTY_ID = 112
     * WHERE t.IBLOCK_ID = 16 AND t.ACTIVE = "Y"
     * ORDER BY t.NAME
     */

//    $order = Array("name" => "asc");
//    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_KPP", "CODE");
//    $arFilter = Array("IBLOCK_ID" => 16, "ACTIVE" => "Y");
//    $res = \CIBlockElement::GetList($order, $arFilter, false, false, $arSelect);
//    while ($ob = $res->GetNextElement())
//    {
//
//      $arProps = $ob->GetFields();
//
//      $allReviews[$arProps['PROPERTY_KPP_VALUE']] = $arProps;
//    }
//    $countReviews = count($allReviews);


//    $arFilter = Array(
//      "IBLOCK_ID" => 13,
//      "ACTIVE" => "Y",
//      "USER_NO_AUTH" => false,
//      "!PROPERTY_VERIFIED" => false,
//    );
//
//    if (isset($sort_url["admin"]))
//    {
//      $arFilter = Array(
//        "IBLOCK_ID" => 13,
//        "ACTIVE" => "Y",
//        $sort_url["admin"] => false,
//        "!USER_NO_AUTH" => false,
//      );
//    } else
//    {
//      foreach ($sort_url as $key => $filter)
//      {
//        $key = mb_strtoupper($key);
//        $arFilter += [$key => $filter];
//      }
//    }
//
//    $order = Array("created" => "desc");
//    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "CREATED_DATE", "PROPERTY_*");
//
//
//    $pagen = Array("nPageSize" => 10);
//    if ($sort_url["comments"] == "all" && !isset($_GET["PAGEN_1"]))
//    {
//      $pagen = false;
//    } else if (!isset($_GET["PAGEN_1"]))
//    {
//
//      $pagen["iNumPage"] = 1;
//    }
//
//
    /*
     * SELECT t.*, bip.*, prop.*
FROM b_iblock_element t
LEFT JOIN b_iblock_element_property prop on prop.IBLOCK_ELEMENT_ID = t.ID
LEFT JOIN b_iblock_property bip ON prop.IBLOCK_PROPERTY_ID = bip.ID
WHERE t.IBLOCK_ID = 13 AND t.ACTIVE = "Y"
ORDER BY t.NAME
     */
//    $res = CIBlockElement::GetList($order, $arFilter, false, $pagen, $arSelect);
//    if (!$sort_url["comments"] == "all")
//    {
//      $res->NavStart(0);
//    }
//    $is_elemnt = true;
//if ($res->SelectedRowsCount() == 0) {
//$is_elemnt = false;

//    while ($ob = $res->GetNextElement())
//    {
//
//      $arFields = $ob->GetFields();
//      $arProps = $ob->GetProperties();
//
//      $newdata = explode(".", $arFields["CREATED_DATE"]);
//      $newstrDate = $newdata[2] . '.' . $newdata[1] . '.' . $newdata[0];
//
//      $newDate = FormatDate("d F, Y", MakeTimeStamp($newstrDate));
//      if ($sort_url["admin"] == "")
//      {
//        if ($arProps["NAME_USER"]["VALUE"] == "" && $arProps["VERIFIED"]["VALUE"] == "")
//        {
//          continue;
//        }
//      }
////print_r($arProps["NAME_USER"]["VALUE"]);
//      if ($arProps["NAME_USER"]["VALUE"] == "")
//      {
//        $name_user = $arProps["USER_NO_AUTH"]["VALUE"];
//      } else
//      {
//        $ID_USER = $arProps["NAME_USER"]["VALUE"];
//        $rsUser = CUser::GetByID($ID_USER);
//        $arUser = $rsUser->Fetch();
//        $name_user = $arUser["NAME"];
//        if ($name_user == ""):
//          $name_user = $arProps["USER_NO_AUTH"]["VALUE"];
//        endif;
//      }
//      if (is_array($arProps["COMMENTS_TO_REWIEW"]["VALUE"]))
//      {
//        $count_comments = count($arProps["COMMENTS_TO_REWIEW"]["VALUE"]);
//      } else
//      {
//        $count_comments = 0;
//      }
//      $city = CIBlockSection::GetByID($arProps["REGION"]["VALUE"])->GetNext();
//      if ($arProps["NAME_COMPANY"]["VALUE"] == "")
//      {
//        continue;
//      }
//      $compani = CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetProperties();
//      $compani_fields = CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetFields();
//
//      $file = CFile::ResizeImageGet($compani["LOGO_IMG"]["VALUE"], array('width' => 120, 'height' => 80),
//        BX_RESIZE_IMAGE_PROPORTIONAL, true);
//

    $reviewListFilter = new FeedbackListFilter();
    $reviewListFilter->setPage($request->query->get('page', 1));

    $reviewListUrlbuilder = new FeedbackListFilterUrlBuilder($reviewListFilter, $this->get('router'));

    $reviewListFilterForm = $this->createForm(FeedbackListFilterType::class, $reviewListFilter, [
      'url_builder' => $reviewListUrlbuilder
    ]);
    $reviewListFilterForm->submit($request->query->get($reviewListFilterForm->getName()));

    $reviewListQb = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository(Feedback::class)
      ->createQueryBuilder('rv')
      ->innerJoin('rv.branch', 'rvb')
      ->innerJoin('rvb.company', 'rvc');

    if ($reviewListFilter->getRating())
    {
      $reviewListQb
      ->andWhere('rv.valuation = :rating')
      ->setParameter('rating', $reviewListFilter->getRating());
    }

    if ($reviewListFilter->getCompany())
    {
      $reviewListQb
        ->andWhere('rvb.company = :company')
        ->setParameter('company', $reviewListFilter->getCompany());
    }

    if ($reviewListFilter->getRegion())
    {
      $reviewListQb
        ->andWhere('rvb.region = :region')
        ->setParameter('region', $reviewListFilter->getRegion());
    }

    if ($reviewListFilter->getModeration())
    {
      $reviewListQb
        ->andWhere('rv.moderation_status = :moderation_status')
        ->setParameter(FeedbackModerationStatus::MODERATION_NONE);
    }

    $maxPerPage = 10;

    $pagination = new Pagination($reviewListQb, $reviewListFilter->getPage(), $maxPerPage);

    $reviews = $pagination->getIterator();

    return $this->render('InsuranceCompany/Review/list.html.twig', [
      'reviews' => $reviews,
      'nbReviews' => $pagination->getNbResults(),
      'pagination' => $pagination,
      'filter' => $reviewListFilter,
      'filterForm' => $reviewListFilterForm->createView(),
      'companyRating' => $this->branchRatingHelper->buildRating($reviewListFilter->getRegion()),
      'urlBuilder' => $reviewListUrlbuilder
    ]);
  }

  /**
   * @Route(path="/add-feedback")
   */
  public function indexAdd()
  {
    return new Response('add-feedback');
  }
}
