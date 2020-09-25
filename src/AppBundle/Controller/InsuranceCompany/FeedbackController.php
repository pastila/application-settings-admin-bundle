<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller\InsuranceCompany;

use AppBundle\Entity\Company\Citation;
use AppBundle\Entity\Company\Comment;
use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use AppBundle\Form\Feedback\FeedbackType;
use AppBundle\Form\InsuranceCompany\FeedbackListFilterType;
use AppBundle\Model\InsuranceCompany\Branch\BranchRatingHelper;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Model\InsuranceCompany\FeedbackListFilterUrlBuilder;
use AppBundle\Model\Pagination;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class FeedbackController extends Controller
{
  private $branchRatingHelper;

  public function __construct(BranchRatingHelper $branchRatingHelper)
  {
    $this->branchRatingHelper = $branchRatingHelper;
  }

  /**
   * @Route(path="/reviews")
   * @Route(path="/companies/{slug}/reviews", name="company_review_list")
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

    $response = new Response();
    $response->setPublic();

    if ($request->query->count() === 0)
    {
      /** @var QueryBuilder $maxQb */
      $maxQb = $maxUpdatedAt = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository(Feedback::class)
        ->createQueryBuilder('rv');

      $maxUpdatedAt = $maxQb
        ->select('MAX(rv.updatedAt)')
        ->getQuery()
        ->getSingleScalarResult();

      $response->setLastModified(new \DateTime($maxUpdatedAt));

      if ($response->isNotModified($request))
      {
        return $response;
      }
    }
    else
    {
      $response->setMaxAge(3600);

      // (optional) set a custom Cache-Control directive
      $response->headers->addCacheControlDirective('must-revalidate', true);
    }


    $reviewListFilter = new FeedbackListFilter();
    $reviewListFilter->setPage($request->query->get('page', 1));

    $reviewListUrlbuilder = new FeedbackListFilterUrlBuilder($reviewListFilter, $this->get('router'),
      $this->get('form.factory'));

    $reviewListFilterForm = $this->createForm(FeedbackListFilterType::class, $reviewListFilter, [
      'url_builder' => $reviewListUrlbuilder
    ]);

    $filterParams = $request->query->get($reviewListFilterForm->getName(), []);

    if ($request->get('slug'))
    {
      $company = $this->getDoctrine()->getManager()->getRepository(Company::class)
        ->findOneBy(['slug' => $request->get('slug')]);

      if (!$company)
      {
        throw $this->createNotFoundException();
      }

      $reviewListFilter->setCompany($company);

      if (!isset($filterParams['company']))
      {
        //Обманываем форму фильтра, чтобы она думала, что мы отправили значение
        $filterParams['company'] = $company->getId();
      }
    }

    $reviewListFilterForm->submit($filterParams);

    /** @var QueryBuilder $reviewListQb */
    $reviewListQb = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository(Feedback::class)
      ->createQueryBuilder('rv');

    $reviewListQb
      ->innerJoin('rv.branch', 'rvb')
      ->innerJoin('rvb.company', 'rvc')
      ->leftJoin('rv.comments', 'rvct')
      ->leftJoin('rvct.citations', 'rvctcs');

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

    if (isset($filterParams['moderation']))
    {
      $reviewListQb
        ->andWhere('rv.moderationStatus = :moderationStatus')
        ->setParameter('moderationStatus', $reviewListFilter->getModeration());
    }

    $maxPerPage = 10;

    $reviewListQb->orderBy('rv.createdAt', 'DESC');

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
    ], $response);
  }

  /**
   * @param $id
   * @Route(path="/reviews/{id}", requirements={ "id": "\d+" })
   */
  public function showAction($id, Request $request)
  {
    /** @var Feedback $review */
    $review = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository(Feedback::class)
      ->createQueryBuilder('rv')
      ->leftJoin('rv.comments', 'rvct')
      ->leftJoin('rvct.citations', 'rvctcs')
      ->andWhere('rv.id = :feedback_id')
      ->setParameter('feedback_id', $id)
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();

    if (!$review)
    {
      throw new NotFoundHttpException(sprintf('Feedback %s not found', $id));
    }

    $response = new Response();
    $response->setLastModified($review->getUpdatedAt());

    // Set response as public. Otherwise it will be private by default.
    $response->setPublic();

    if ($response->isNotModified($request))
    {
      return $response;
    }

    return $this->render('InsuranceCompany/Review/show.html.twig', [
      'review' => $review
    ], $response);
  }

  /**
   * @Route(path="/reviews/add", name="app_insurancecompany_feedback_new")
   */
  public function newAction(Request $request, UserInterface $user = null)
  {
    $feedback = new Feedback();
    $form = $this->createForm(FeedbackType::class, $feedback, [
      'csrf_protection' => false,
    ]);

    /*** Data Adapter */
    if ($request->isMethod('post')) {
      $data = $request->request->all();
      $userId = null !== $user ? $user->getId() : null;
      $data_form = [
        'author' => $userId,
        'region' => $data['region_select_id'],
        'branch' => $data['company_select_id'],
        'text' => $data['feedback']['text'],
        'title' => $data['feedback']['title'],
        'valuation' => $data['rating_select'],
      ];
    }

    if ($request->isMethod('post')) { //$form->isValid() todo
      $sql = 'INSERT INTO s_company_feedbacks(user_id, region_id, branch_id, title, text, valuation, moderation_status, created_at, updated_at) 
              VALUES(:author, :region, :branch, :title, :text, :valuation, :moderation_status, :createdAt, :createdAt)';
      $entityManager = $this->getDoctrine()->getManager();
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindValue('author', $data_form['author']);
      $stmt->bindValue('region', $data_form['region']);
      $stmt->bindValue('branch', $data_form['branch']);
      $stmt->bindValue('text', $data_form['text']);
      $stmt->bindValue('title', $data_form['title']);
      $stmt->bindValue('valuation', $data_form['valuation']);
      $stmt->bindValue('moderation_status', FeedbackModerationStatus::MODERATION_NONE);
      $stmt->bindValue('createdAt', date("Y-m-d H:i:s"));
      $stmt->execute();

      return $this->redirectToRoute('app_insurancecompany_feedback_index');
    }

    return $this->render('InsuranceCompany/Review/new.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route(path="/reviews/add-comment", name="add_comment")
   */
  public function addCommentAction(Request $request, UserInterface $user = null)
  {
    $data = $request->request->all();
    $userId = null !== $user ? $user->getId() : null;
    $user = $this->getDoctrine()->getManager()->getRepository(User::class)
      ->findOneBy(['id' => $userId]);
    $text = isset($data['comment']) ? $data['comment'] : null;
    $review_id = isset($data['review_id']) ? $data['review_id'] : null;
    $feedback = $this->getDoctrine()->getManager()->getRepository(Feedback::class)
      ->findOneBy(['id' => $review_id]);

    $comment = new Comment();
    $comment->setUser($user);
    $comment->setText($text);
    $comment->setFeedback($feedback);
    $comment->setModerationStatus(FeedbackModerationStatus::MODERATION_NONE);
    $comment->setCreatedAt(new \DateTime());
    $comment->setUpdatedAt(new \DateTime());

    $this->getDoctrine()->getManager()->persist($comment);
    $this->getDoctrine()->getManager()->flush();

    return $this->redirectToRoute('app_insurancecompany_feedback_index', [], 302);
  }

  /**
   * @Route(path="/reviews/remove-comment", name="remove_comment_ajax")
   */
  public function removeCommentAction(Request $request)
  {
    if ($request->isXmlHttpRequest()) {
      $data = $request->request->all();
      $comment_id = isset($data['id']) ? $data['id'] : null;

      /**
       * @var Comment $comment
       */
      $comment = $this->getDoctrine()->getManager()->getRepository(Comment::class)
        ->findOneBy(['id' => $comment_id]);
      if (!empty($comment)) {
        $em = $this->getDoctrine()->getEntityManager();
        foreach ($comment->getCitations() as $citation) {
          $em->remove($citation);
        }
        $em->remove($comment);
        $em->flush();
      }

      return new JsonResponse(1);
    }
  }

  /**
   * @Route(path="/reviews/add-citation", name="add_citation_ajax")
   */
  public function addCitationAction(Request $request, UserInterface $user = null)
  {
    if ($request->isXmlHttpRequest()) {
      $data = $request->request->all();
      $comment_id = isset($data['id_comment']) ? $data['id_comment'] : null;
      $message = isset($data['message']) ? $data['message'] : null;

      $comment = $this->getDoctrine()->getManager()->getRepository(Comment::class)
        ->findOneBy(['id' => $comment_id]);
      if (!empty($comment)) {
        $userId = null !== $user ? $user->getId() : null;
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)
          ->findOneBy(['id' => $userId]);

        $representative = false;
        if ($user->getRepresentative()){
          $feedback = $comment->getFeedback();
          if (!empty($feedback)) {
            $branch = $feedback->getBranch();
            if (!empty($branch) && !empty($user->getBranch()) && $user->getBranch()->getId() === $branch->getId()){
              $representative = true;
            }
          }
        }

        $citation = new Citation();
        $citation->setUser($user);
        $citation->setComment($comment);
        $citation->setText($message);
        $citation->setRepresentative($representative);
        $citation->setCreatedAt(new \DateTime());
        $citation->setUpdatedAt(new \DateTime());

        $this->getDoctrine()->getManager()->persist($citation);
        $this->getDoctrine()->getManager()->flush();
      }

      return new JsonResponse(1);
    }
  }

  /**
   * @Route(path="/reviews/remove-citation", name="remove_citation_ajax")
   */
  public function removeCitationAction(Request $request)
  {
    if ($request->isXmlHttpRequest()) {
      $data = $request->request->all();
      $citation_id = isset($data['id']) ? $data['id'] : null;

      $citation = $this->getDoctrine()->getManager()->getRepository(Citation::class)
        ->findOneBy(['id' => $citation_id]);
      if (!empty($citation)) {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($citation);
        $em->flush();
      }

      return new JsonResponse(1);
    }
  }

  /**
   * @Route(path="/reviews/region-select", name="feedback_region_select_ajax")
   */
  public function regionSelectAction(Request $request)
  {
    if ($request->isXmlHttpRequest()) {
      $data = $request->request->all();
      $region_id = isset($data['region_id']) ? $data['region_id'] : null;

      $branches = $this->getDoctrine()->getManager()->getRepository(CompanyBranch::class)
        ->findBy([
          'region' => $region_id
        ]);
      $content = '';
      foreach ($branches as $branch) {
        $content .= '<li value="'. $branch->getId() .'" class="custom-serach__items_item hospital company-select-item
                      data-kpp="' .$branch->getKpp() . '">' .
          $branch->getName() .'</li>';
      }
      $response = new Response();
      $response->setContent($content);

      return $response;
    }
  }

  /**
   * @Route(path="/reviews/admin-check", name="admin_check_ajax")
   */
  public function adminCheckAction(Request $request)
  {
    if ($request->isXmlHttpRequest()) {
      $data = $request->request->all();
      $id = !empty($data['id']) ? $data['id'] : null;
      $accepted = !empty($data['accepted']) ? $data['accepted'] : null;
      $reject = !empty($data['reject']) ? $data['reject'] : null;
      $status = !empty($accepted) ?
        FeedbackModerationStatus::MODERATION_ACCEPTED :
        (!empty($reject) ? FeedbackModerationStatus::MODERATION_REJECTED : FeedbackModerationStatus::MODERATION_NONE);

      $feedback = $this->getDoctrine()->getManager()->getRepository(Feedback::class)
        ->findOneBy(['id' => $id]);
      if (!empty($feedback)) {
        $feedback->setModerationStatus($status);
        $this->getDoctrine()->getManager()->persist($feedback);
        $this->getDoctrine()->getManager()->flush();

        // update rating company and branch
      }

      return new JsonResponse(1);
    }
  }
}
