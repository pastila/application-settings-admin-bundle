<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller\User;


use Accurateweb\ClientApplicationBundle\DataAdapter\ClientApplicationModelTransformer;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaint;
use AppBundle\Entity\User\User;
use AppBundle\Form\Obrashcheniya\OmsChargeComplaint1stStepType;
use AppBundle\Model\Filter\FeedbackFilter;
use AppBundle\Model\GroupedPagination;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Model\InsuranceCompany\FeedbackListFilterUrlBuilder;
use AppBundle\Model\InsuranceCompany\OmsChargeComplaintFilter;
use AppBundle\Model\Pagination;
use AppBundle\Repository\Company\FeedbackRepository;
use AppBundle\Repository\OmsChargeComplaint\OmsChargeComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CabinetController extends AbstractController
{
  private $reviewRepository;

  private $omsChargeComplaintRepository;

  private $validator;

  public function __construct(
      FeedbackRepository $feedbackRepository,
      OmsChargeComplaintRepository $omsChargeComplaintRepository,
      ValidatorInterface $validator
  )
  {
    $this->reviewRepository = $feedbackRepository;
    $this->omsChargeComplaintRepository = $omsChargeComplaintRepository;
    $this->validator = $validator;
  }

  /**
   * Личный кабинет
   * @Route(name="lk_index", path="/lk")
   * @param Request $request
   */
  public function cabinetAction(Request $request)
  {
    $this->denyAccessUnlessGranted(['ROLE_USER']);

    return $this->render('AppBundle:Lk:index.html.twig');
  }

  public function _dashboardAppealListAction()
  {
    $filter = new OmsChargeComplaintFilter();
    $filter->setPage(1);
    $filter->setPerPage(10);
    $filter->setUser($this->getUser());
    $filter->setStatuses([OmsChargeComplaint::STATUS_CREATED, OmsChargeComplaint::STATUS_SENT]);
    $appealListQb = $this->getDoctrine()
      ->getRepository('AppBundle:OmsChargeComplaint\OmsChargeComplaint')
      ->createQueryBuilderByFilter($filter);

//    $reviewListUrlbuilder = new FeedbackListFilterUrlBuilder($filter, $this->get('router'),
//      $this->get('form.factory'), 'app_user_cabinet_feedback');

    $appealListQb->orderBy('ap.createdAt', 'DESC');
    $pagination = new GroupedPagination($appealListQb, $filter->getPage(), $filter->getPerPage());
    $omsChargeComplaints = $pagination->getIterator();

    return $this->render('AppBundle:Lk:_dashboard_appeal_list.html.twig', [
      'omsChargeComplaints' => $omsChargeComplaints,
      'currentYear' => null,
    ]);
  }

  /**
   * Личный кабинет
   * @Route(name="lk_my_appeal_list", path="/lk/my-appeals")
   * @Route(name="api_appeal_list", path="/api/v1/lk/my-appeals", defaults={"_api"=true})
   * @param Request $request
   */
  public function cabinetMyAppealListAction(Request $request)
  {
    $this->denyAccessUnlessGranted(['ROLE_USER']);

    $appealForm = $this->createForm(OmsChargeComplaint1stStepType::class);
    $user = $this->getUser();
    $filter = new OmsChargeComplaintFilter();
    $filter->setPage($request->query->get('page', 1));
    $filter->setPerPage(10);
    $filter->setUser($user);

    $appealListQb = $this
      ->omsChargeComplaintRepository
      ->createQueryBuilderByFilter($filter);

//    $reviewListUrlbuilder = new FeedbackListFilterUrlBuilder($filter, $this->get('router'),
//      $this->get('form.factory'), 'app_user_cabinet_feedback');

    $appealListQb->orderBy('ap.createdAt', 'DESC');
    $pagination = new GroupedPagination($appealListQb, $filter->getPage(), $filter->getPerPage());

    if ($request->get('_api'))
    {
      return $this->json($this->get('aw.client_application.transformer')->getClientModelData($pagination, 'pagination', [
        'adapter' => 'appeal',
      ]));
    }

    /** @var OmsChargeComplaint|null $lastAppealOnPreviousPage */
    $lastAppealOnPreviousPage = $pagination->getLastItemOnPreviousPage();
    $previousYear = $lastAppealOnPreviousPage ? $lastAppealOnPreviousPage->getYear() : null;

    return $this->render('AppBundle:Lk:my_appeal_list.html.twig', [
      'appealForm' => $appealForm->createView(),
      'omsChargeComplaints' => $pagination,
      'currentYear' => $previousYear,
    ]);
  }


  /**
   * Личный кабинет
   * @Route(name="lk_my_appeal_show", path="/lk/my-appeals/{id}")
   * @Route(name="api_appeal_show", path="/api/v1/lk/my-appeals/{id}", defaults={"_api"=true})
   * @param Request $request
   */
  public function cabinetMyAppealShowAction(Request $request, $id)
  {
    $appeal = $this->getDoctrine()->getRepository('AppBundle:OmsChargeComplaint\OmsChargeComplaint')->find($id);

    if ($appeal === null)
    {
      throw new NotFoundHttpException(sprintf('Appeal %s not found', $id));
    }

    if ($appeal->getStatus() === OmsChargeComplaint::STATUS_DRAFT)
    {
      throw new NotFoundHttpException(sprintf('Appeal %s not complete', $id));
    }

    if ($request->get('_api'))
    {
      return $this->json($this->get('aw.client_application.transformer')->getClientModelData($appeal, 'appeal'));
    }

    $appealFilter = new OmsChargeComplaintFilter();
    $appealFilter->setUser($this->getUser());
    $appealFilter->setStatuses([OmsChargeComplaint::STATUS_CREATED, OmsChargeComplaint::STATUS_SENT]);
    $appealsQb = $this->getDoctrine()
      ->getRepository('AppBundle:OmsChargeComplaint\OmsChargeComplaint')
      ->createQueryBuilderByFilter($appealFilter);
    $appealsQb->andWhere('ap != :appeal');
    $appealsQb->setParameter('appeal', $appeal);
    $beforeQb = clone $appealsQb;
    $afterQb = clone $appealsQb;
    $beforeQb
      ->andWhere('ap.createdAt >= :created')
      ->addOrderBy('ap.createdAt', 'ASC')
      ->setParameter('created', $appeal->getCreatedAt())
      ->setMaxResults(2);
    $afterQb
      ->andWhere('ap.createdAt <= :created')
      ->addOrderBy('ap.createdAt', 'DESC')
      ->setParameter('created', $appeal->getCreatedAt())
      ->setMaxResults(2);

    $before = $beforeQb->getQuery()->getResult();
    $after = $afterQb->getQuery()->getResult();
    $appeals = array_merge(array_reverse($before), [$appeal], $after);

    return $this->render('AppBundle:Lk:my_appeal_show.html.twig', [
      'appeal' => $appeal,
      'appeals' => $appeals,
    ]);
  }


  /**
   * Личный кабинет
   * @Route(name="lk_my_review_list", path="/lk/my-reviews")
   * @param Request $request
   */
  public function cabinetMyReviewListAction(Request $request)
  {
    $filter = new FeedbackFilter();
    $filter->setAuthor($this->getUser());
    $reviews = $this->getDoctrine()
      ->getRepository('AppBundle:Company\Feedback')
      ->createQueryBuilderByFilter($filter)
      ->addOrderBy('f.createdAt', 'DESC')
      ->getQuery()
      ->getResult()
    ;

    return $this->render('AppBundle:Lk:my_review_list.html.twig', [
      'reviews' => $reviews,
    ]);
  }

  /*
  * Страница отзыва
  * @Route(name="lk_my_review_show", path="/lk/my-reviews/review-item")
  * @param Request $request
  */
  public function cabinetMyReviewShowAction(Request $request)
  {
    // $this->denyAccessUnlessGranted(['ROLE_USER']);

    return $this->render('AppBundle:Lk:my_review_show.html.twig');
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

  public static function getSubscribedServices ()
  {
    return array_merge(parent::getSubscribedServices(), [
      'aw.client_application.transformer' => ClientApplicationModelTransformer::class,
    ]);
  }
}
