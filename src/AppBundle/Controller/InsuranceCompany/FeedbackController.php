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
use AppBundle\Entity\User\User;
use AppBundle\Form\Feedback\CommentFormType;
use AppBundle\Form\Feedback\FeedbackType;
use AppBundle\Form\InsuranceCompany\FeedbackListFilterType;
use AppBundle\Model\InsuranceCompany\Branch\BranchRatingHelper;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Model\InsuranceCompany\FeedbackListFilterUrlBuilder;
use AppBundle\Model\Pagination;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FeedbackController
 * @package AppBundle\Controller\InsuranceCompany
 */
class FeedbackController extends Controller
{
  private $branchRatingHelper;

  public function __construct(BranchRatingHelper $branchRatingHelper)
  {
    $this->branchRatingHelper = $branchRatingHelper;
  }

  /**
   * @Route(path="/feedback")
   * @Route(path="/companies/{slug}/feedback", name="company_review_list")
   */
  public function indexAction(Request $request, UserInterface $user = null)
  {
    $response = new Response();
    $response->setPublic();

    if ($request->query->count() === 0)
    {
      /** @var QueryBuilder $maxQb */
      $maxQb = $maxUpdatedAt = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository(Feedback::class)
        ->createQueryBuilder('rv')
        ->leftJoin('rv.comments', 'rvct');

      $maxUpdatedAt = $maxQb
        ->select('MAX(rv.updatedAt), MAX(rvct.updatedAt)')
        ->getQuery()
        ->getResult();
      $rvUpdateAt = !empty($maxUpdatedAt[0][1]) ? $maxUpdatedAt[0][1] : null;
      $rvctUpdateAt = !empty($maxUpdatedAt[0][2]) ? $maxUpdatedAt[0][2] : null;
      $max = $rvUpdateAt > $rvctUpdateAt ? $rvUpdateAt : $rvctUpdateAt;

      $response->setLastModified(new \DateTime($max));

      if ($response->isNotModified($request))
      {
        return $response;
      }
    } else
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
      ->orWhere('rv.moderationStatus = :status')
      ->setParameter('status', FeedbackModerationStatus::MODERATION_ACCEPTED);

    $userId = (null !== $user) ? $user->getId() : null;
    if (!empty($userId)) {
      $reviewListQb
        ->orWhere('rv.author = :user_id')
        ->setParameter('user_id', $userId);
    }

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

    $title = 'Отзывы о страховых медицинских организациях' . (($pagination->getPage() > 1) ? ' — Страница ' . $pagination->getPage() : '');

    if ($reviewListFilter->getCompany())
    {
      $title = 'Отзывы о страховой медицинской организации &laquo;'.$reviewListFilter->getCompany()->getName().'&raquo;' . (($pagination->getPage() > 1) ? ' — Страница ' . $pagination->getPage() : '');
    }

    return $this->render('InsuranceCompany/Review/list.html.twig', [
      'reviews' => $reviews,
      'nbReviews' => $pagination->getNbResults(),
      'pagination' => $pagination,
      'filter' => $reviewListFilter,
      'filterForm' => $reviewListFilterForm->createView(),
      'companyRating' => $this->branchRatingHelper->buildRating($reviewListFilter->getRegion()),
      'urlBuilder' => $reviewListUrlbuilder,
      'title' => $title
    ], $response);
  }

  /**
   * @param $id
   * @Route(path="/feedback/{id}", requirements={ "id": "\d+" })
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
   * @Route(path="/feedback/add", name="app_insurancecompany_feedback_new")
   */
  public function newAction(Request $request, UserInterface $user = null)
  {
    $feedback = new Feedback();
    $form = $this->createForm(FeedbackType::class, $feedback, [
      'csrf_protection' => false,
    ]);
    $data = $request->request->all();
    $userId = null !== $user ? $user->getId() : null;

    if ($request->isMethod('post')) {
      $newData = [
        'author' => $userId,
        'region' => $data['region_select_id'],
        'branch' => $data['company_select_id'],
        'valuation' => $data['rating_select'],
      ];
      $newData = array_merge($data['feedback'], $newData);
      $form->submit($newData);
    }
    if ($form->isSubmitted() && !empty($newData['region']) && !empty($newData['branch']))
    {
      $sql = 'INSERT INTO s_company_feedbacks(user_id, region_id, branch_id, author_name, title, text, valuation, moderation_status, created_at, updated_at) 
              VALUES(:author, :region, :branch, :author_name, :title, :text, :valuation, :moderation_status, :createdAt, :createdAt)';
      $entityManager = $this->getDoctrine()->getManager();
      $stmt = $entityManager->getConnection()->prepare($sql);
      $stmt->bindValue('author', $newData['author']);
      $stmt->bindValue('author_name', $newData['author_name']);
      $stmt->bindValue('region', $newData['region']);
      $stmt->bindValue('branch', $newData['branch']);
      $stmt->bindValue('text', $newData['text']);
      $stmt->bindValue('title', $newData['title']);
      $stmt->bindValue('valuation', $newData['valuation']);
      $stmt->bindValue('moderation_status', FeedbackModerationStatus::MODERATION_NONE);
      $stmt->bindValue('createdAt', date("Y-m-d H:i:s"));
      $stmt->execute();
      $id = $entityManager->getConnection()->lastInsertId();
      $feedback = $this->getDoctrine()->getManager()->getRepository(Feedback::class)
        ->findOneBy(['id' => $id]);

      $branch = $feedback->getBranch();
      $company = !empty($branch) ? $branch->getCompany() : null;
      $emails = [];
      if (!empty($company->getEmailFirst()))
      {
        $emails[] = $company->getEmailFirst();
      }
      if (!empty($company->getEmailSecond()))
      {
        $emails[] = $company->getEmailSecond();
      }
      if (!empty($company->getEmailThird()))
      {
        $emails[] = $company->getEmailThird();
      }
      $url = $this->generateUrl('app_insurancecompany_feedback_show', ['id' => $feedback->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
      $date = $feedback->getCreatedAt()->format('Y-m-d H:i:s');

      try
      {
        $message = (new \Swift_Message('Новый отзыв'))
          ->setFrom($this->container->getParameter('mailer_from'))
          ->setTo($emails)
          ->setBody(
            $this->renderView(
              'emails/feedback/new_for_boss.html.twig', [
                'url' => $url,
                'date' => $date,
              ]
            ),
            'text/html'
          );
        $this->get('mailer')->send($message);
      } catch (\Exception $e)
      {
        $logger = $this->get('logger');
        $logger->error('No send mail in admin-check:' . $e);
      }

      return $this->redirectToRoute('app_insurancecompany_feedback_index');
    }

    return $this->render('InsuranceCompany/Review/new.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route(path="/feedback/add-comment", name="add_comment")
   */
  public function addCommentAction(Request $request, UserInterface $user = null)
  {
    $data = $request->request->all();
    $comment = new Comment();
    $form = $this->createForm(CommentFormType::class, $comment, [
      'csrf_protection' => false,
    ]);
    $form->submit([
      'feedback' => !empty($data['review_id']) ? $data['review_id'] : null,
      'text' => !empty($data['comment']) ? $data['comment'] : null,
    ]);

    if ($form->isSubmitted() && $form->isValid())
    {
      $userId = null !== $user ? $user->getId() : null;
      $user = $this->getDoctrine()->getManager()->getRepository(User::class)
        ->findOneBy(['id' => $userId]);

      $comment = $form->getData();
      $comment->setUser($user);
      $comment->setModerationStatus(FeedbackModerationStatus::MODERATION_NONE);
      $comment->setCreatedAt(new \DateTime());
      $comment->setUpdatedAt(new \DateTime());

      $em = $this->getDoctrine()->getManager();
      $em->persist($comment);
      $em->flush();

      return new JsonResponse(1);
    }

    return new JsonResponse([
      //errors
    ], 400);
  }

  /**
   * @Route(path="/feedback/remove-comment", name="remove_comment_ajax")
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
   * @Route(path="/feedback/add-citation", name="add_citation_ajax")
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
    }

    return new JsonResponse(1);
  }

  /**
   * @Route(path="/feedback/remove-citation", name="remove_citation_ajax")
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
   * @Route(path="/feedback/region-select", name="feedback_region_select_ajax")
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
   * @Route(path="/feedback/admin-check", name="admin_check_ajax")
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
        $this->updateRating($feedback);
      }

      return new JsonResponse(1);
    }
  }

  /**
   * @param Feedback $feedback
   */
  public function updateRating($feedback)
  {
    $branch = $this->updateRatingObject(
      Feedback::class,
      'branch',
      $feedback->getBranch(),
      CompanyBranch::class);
    $this->getDoctrine()->getManager()->persist($branch);
    $this->getDoctrine()->getManager()->flush();

    $company = $this->updateRatingObject(
      CompanyBranch::class,
      'company'
      , $feedback->getCompany(),
      Company::class);
    $this->getDoctrine()->getManager()->persist($company);
    $this->getDoctrine()->getManager()->flush();
  }

  /**
   * @param $repo
   * @param $param
   * @param $model
   * @param $class
   * @return object|null
   */
  public function updateRatingObject($repo, $param, $model, $class)
  {
    $items = $this->getDoctrine()->getManager()->getRepository($repo)
      ->createQueryBuilder('t')
      ->where('t.' .$param. ' = :model')
      ->andWhere('t.valuation > 0')
      ->setParameter('model', $model)
      ->getQuery()
      ->getResult();

    $valuationAll = 0;
    foreach ($items as $key => $item)
    {
      $valuationAll += $item->getValuation();
    }
    $valuation = (float)($valuationAll / count($items));
    $branch = $this->getDoctrine()->getManager()->getRepository($class)
      ->findOneBy([
        'id' => $model->getId()
      ]);
    $branch->setValuation($valuation);

    return $branch;
  }
}
