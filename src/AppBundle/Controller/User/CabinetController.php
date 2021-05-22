<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller\User;


use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\User\User;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Model\InsuranceCompany\FeedbackListFilterUrlBuilder;
use AppBundle\Model\Pagination;
use AppBundle\Repository\Company\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CabinetController extends AbstractController
{
  private $reviewRepository;

  private $validator;

  public function __construct(
      FeedbackRepository $feedbackRepository,
      ValidatorInterface $validator
  )
  {
    $this->reviewRepository = $feedbackRepository;
    $this->validator = $validator;
  }

  /**
   * Личный кабинет
   * @Route(name="lk", path="/lk")
   * @param Request $request
   */
  public function cabinetAction(Request $request)
  {
    $this->denyAccessUnlessGranted(['ROLE_USER']);

    return $this->render('AppBundle:Lk:index.html.twig');
  }


  /**
   * Личный кабинет
   * @Route(name="lk/my-appeals", path="/lk/my-appeals")
   * @param Request $request
   */
  public function cabinetMyAppealListAction(Request $request)
  {
    // $this->denyAccessUnlessGranted(['ROLE_USER']);

    return $this->render('AppBundle:Lk:my_appeal_list.html.twig');
  }

    /**
   * Личный кабинет - Ваши отзывы
   * @Route(path="/cabinet/feedback")
   */
  public function feedbackAction(Request $request)
  {
    $this->denyAccessUnlessGranted(User::ROLE_USER);

    $user = $this->getUser();

    $reviewListQb = $this
      ->reviewRepository
      ->createQueryBuilder('rv')
      ->innerJoin('rv.branch', 'b')
      ->where('rv.author = :author')
      ->setParameters([
        ':author' => $user
      ]);

    $reviewListFilter = new FeedbackListFilter();
    $reviewListFilter->setPage($request->query->get('page', 1));

    $reviewListUrlbuilder = new FeedbackListFilterUrlBuilder($reviewListFilter, $this->get('router'),
      $this->get('form.factory'), 'app_user_cabinet_feedback');

    $maxPerPage = 10;

    $reviewListQb->orderBy('rv.createdAt', 'DESC');

    $pagination = new Pagination($reviewListQb, $reviewListFilter->getPage(), $maxPerPage);

    $reviews = $pagination->getIterator();

    return $this->render('Cabinet/reviews.html.twig', [
      'reviews' => $reviews,
      'nbReviews' => count($reviews),
      'title' => 'Ваши отзывы — Кабинет пользователя — БезБахил',
      'pagination' => $pagination,
      'urlBuilder' => $reviewListUrlbuilder,
    ]);
  }

  /**
   * Обновление оценки пользователем
   * @Route(path="/cabinet/feedback/{id}/rating")
   */
  public function updateRatingAction($id, Request $request)
  {
    $this->denyAccessUnlessGranted('ROLE_USER');

    $user = $this->getUser();

    /**
     * @var Feedback $review
     */
    $review = $this->reviewRepository->findOneBy([
      'author' => $user,
      'id' => $id
    ]);

    if (!$review)
    {
      throw new NotFoundHttpException(sprintf('Review %s not found or was not authored by user %s', $id, $user));
    }

    $previousValue = $review->getValuation();

    $review->setValuation($request->get('star'));

    $errors = $this->validator->validate($review);
    if (count($errors))
    {
      return new JsonResponse([
        'errors' => (string)$errors
      ], 400);
    }

    if ($previousValue === $review->getValuation())
    {
      return new Response("0");
    }

    $em = $this->getDoctrine()->getManager();
    $em->persist($review);
    $em->flush();

    return new Response("1");

    /*require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    CModule::IncludeModule("iblock");
    global $USER;
    $section = new CIBlockSection();
    $el = new CIBlockElement();
    $msg = "";
    $date_change = date("d.m.Y H:i:s");

    $date["DATE_CHANGE_BY_USER"] = $date_change;
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_EVALUATION");
    $arFilter = Array("IBLOCK_ID" => 13, "ID" => $_POST['id_rewievs']);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while ($ob = $res->GetNextElement())
    {
      $arProps = $ob->GetFields();

      if ((int)$arProps["PROPERTY_EVALUATION_VALUE"] == (int)$_POST["star"])
      {
        echo "0";
      } else
      {
        if ($_POST["star"] != 0)
        {

          $Prop["EVALUATION"] = $_POST["star"];

          CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $Prop);
          CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $date);

          $prop = CIBlockElement::GetByID($_POST['id_rewievs'])->GetNextElement()->GetProperties();

          $id_company = $prop["NAME_COMPANY"]["VALUE"];
          $arSelect_otzev = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
          $arFilter_otzev = Array(
            "IBLOCK_ID" => 13,
            "PROPERTY_NAME_COMPANY" => $id_company,
            "!PROPERTY_VERIFIED" => false,
            "PROPERTY_REJECTED" => false,
            "!PROPERTY_EVALUATION" => 0,
            "ACTIVE" => "Y",
          );
          $res_otzev = CIBlockElement::GetList(Array(), $arFilter_otzev, false, false, $arSelect_otzev);
          $total = 0;
          $count_otzev = $res_otzev->SelectedRowsCount();

          while ($ob_otzev = $res_otzev->GetNextElement())
          {

            $arProp__otzev = $ob_otzev->GetProperties();

            $total = $total + (int)$arProp__otzev["EVALUATION"]["VALUE"];

          }
          $result = $total / $count_otzev;

          $star_clear = Array(
            "AMOUNT_STAR" => round($result, 2),
          );
          CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);

          $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_AMOUNT_STAR");
          $arFilter = Array("IBLOCK_ID" => 16, "ACTIVE" => "Y", "PROPERTY_KPP" => $prop["KPP"]["VALUE"], "!PROPERTY_AMOUNT_STAR" => false);
          $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
          $count_company_with_this_kpp = $res->SelectedRowsCount();

          $total_star = 0;
          $result = 0;
          $arProps_company = array();
          $array_id_company_with_kpp = array();
          while ($ob = $res->GetNextElement())
          {
            $arProps_company = $ob->GetFields();
            array_push($array_id_company_with_kpp, $arProps_company["ID"]);
            $total_star = $total_star + $arProps_company["PROPERTY_AMOUNT_STAR_VALUE"];
          }


          $result = $total_star / $count_company_with_this_kpp;

          $All_star = Array(
            "ALL_AMOUNT_STAR" => round($result, 2),
          );
          foreach ($array_id_company_with_kpp as $key)
          {
            CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
          }

          echo "1";
        } elseif ($_POST["star"] == (int)0)
        {
          $Prop["EVALUATION"] = $_POST["star"];
          CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $Prop);
          CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $date);

          $prop = CIBlockElement::GetByID($_POST['id_rewievs'])->GetNextElement()->GetProperties();
          $id_company = $prop["NAME_COMPANY"]["VALUE"];

          $arSelect_otzev = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
          $arFilter_otzev = Array(
            "IBLOCK_ID" => 13,
            "PROPERTY_NAME_COMPANY" => $id_company,
            "!PROPERTY_VERIFIED" => false,
            "PROPERTY_REJECTED" => false,
            "!PROPERTY_EVALUATION" => 0,
            "ACTIVE" => "Y",
          );
          $res_otzev = CIBlockElement::GetList(Array(), $arFilter_otzev, false, false, $arSelect_otzev);
          $total = 0;
          $count_otzev = $res_otzev->SelectedRowsCount();

          while ($ob_otzev = $res_otzev->GetNextElement())
          {

            $arProp__otzev = $ob_otzev->GetProperties();

            $total = $total + (int)$arProp__otzev["EVALUATION"]["VALUE"];

          }
          $result = $total / $count_otzev;

          $star_clear = Array(
            "AMOUNT_STAR" => round($result, 2),
          );
          CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);

          $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_AMOUNT_STAR");
          $arFilter = Array("IBLOCK_ID" => 16, "ACTIVE" => "Y", "PROPERTY_KPP" => $prop["KPP"]["VALUE"], "!PROPERTY_AMOUNT_STAR" => false);
          $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
          $count_company_with_this_kpp = $res->SelectedRowsCount();
          $total_star = 0;
          $result = 0;
          $arProps_company = array();
          $array_id_company_with_kpp = array();
          while ($ob = $res->GetNextElement())
          {
            $arProps_company = $ob->GetFields();
            array_push($array_id_company_with_kpp, $arProps_company["ID"]);
            $total_star = $total_star + $arProps_company["PROPERTY_AMOUNT_STAR_VALUE"];
          }


          $result = $total_star / $count_company_with_this_kpp;
          $All_star = Array(
            "ALL_AMOUNT_STAR" => round($result, 2),
          );
          foreach ($array_id_company_with_kpp as $key)
          {
            CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
          }


          echo "1";
        }
      }
    }*/

  }
}
