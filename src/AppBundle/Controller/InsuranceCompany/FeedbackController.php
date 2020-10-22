<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller\InsuranceCompany;

use AppBundle\Entity\Company\Citation;
use AppBundle\Entity\Company\Comment;
use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\CompanyBranch;
use AppBundle\Entity\Company\CompanyStatus;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\User\User;
use AppBundle\Exception\BitrixRequestException;
use AppBundle\Form\Feedback\CommentFormType;
use AppBundle\Form\Feedback\FeedbackType;
use AppBundle\Form\InsuranceCompany\FeedbackListFilterType;
use AppBundle\Helper\GetMessFromBitrix;
use AppBundle\Model\InsuranceCompany\Branch\BranchRatingHelper;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Model\InsuranceCompany\FeedbackListFilterUrlBuilder;
use AppBundle\Model\Pagination;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
  private $mainMail;

  public function __construct(
    BranchRatingHelper $branchRatingHelper,
    GetMessFromBitrix $mainMail
  )
  {
    $this->branchRatingHelper = $branchRatingHelper;
    $this->mainMail = $mainMail;
  }

  /**
   * @Route(path="/feedback")
   * @Route(path="/companies/{slug}/feedback", name="company_review_list")
   */
  public function indexAction(Request $request)
  {
    $response = new Response();
    $response->setPublic();

    /** @var FlashBagInterface $flashbag */
    $flashbag = $request->getSession()->getFlashBag();

    //Не кешируем страницу, если перешли со страницы создания отзыва, чтобы не закешировать диалог об успешной отправке отзыва
    if (!$this->getUser() && !$flashbag->has('magnific'))
    {
      /** @var QueryBuilder $maxQb */
      $maxQb = $this
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
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
      }
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

    if (isset($filterParams['moderation']))
    {
      $reviewListQb
        ->andWhere('rv.moderationStatus = :moderationStatus')
        ->setParameter('moderationStatus', $reviewListFilter->getModeration());
    } else
    {
      $reviewListQb
        ->andWhere('rv.moderationStatus = :status')
        ->setParameter('status', FeedbackModerationStatus::MODERATION_ACCEPTED);
    }

    $reviewListQb
      ->innerJoin('rv.branch', 'rvb')
      ->innerJoin('rvb.company', 'rvc')
      ->andWhere('rvb.status = :status')
      ->andWhere('rvc.status = :status')
      ->setParameter('status', CompanyStatus::ACTIVE);
//      ->leftJoin('rv.comments', 'rvct')
//      ->leftJoin('rvct.citations', 'rvctcs')
    ;

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

    $maxPerPage = 10;

    $reviewListQb->orderBy('rv.createdAt', 'DESC');

    $pagination = new Pagination($reviewListQb, $reviewListFilter->getPage(), $maxPerPage);

    $reviews = $pagination->getIterator();

    $title = 'Отзывы о страховых медицинских организациях' . (($pagination->getPage() > 1) ? ' — Страница ' . $pagination->getPage() : '');

    if ($reviewListFilter->getCompany())
    {
      $title = 'Отзывы о страховой медицинской организации &laquo;' . $reviewListFilter->getCompany()->getName() . '&raquo;' . (($pagination->getPage() > 1) ? ' — Страница ' . $pagination->getPage() : '');
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
   * @Route(path="/feedback/comment-{id}", name="company_feedback_old", requirements={ "id": "\d+" })
   */
  public function commentAction(Request $request)
  {
    $feedbackBitrixId = $request->get('id');
    $feedback = $this->getDoctrine()->getManager()
      ->getRepository(Feedback::class)
      ->findOneBy(['bitrixId' => $feedbackBitrixId]);
    if ($feedback)
    {
      return $this->redirectToRoute('app_insurancecompany_feedback_show', [
        'id' => $feedback->getId()
      ], 301);
    }

    return new Response(null, 404);
  }

  /**
   * @param $id
   * @Route(path="/feedback/{id}", requirements={ "id": "\d+" })
   */
  public function showAction($id, Request $request, UserInterface $user = null)
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
    $response->setPublic();
    if (null === $user)
    {
      $response->setLastModified($review->getUpdatedAt());

      if ($response->isNotModified($request))
      {
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
      }
    }


    return $this->render('InsuranceCompany/Review/show.html.twig', [
      'review' => $review
    ], $response);
  }

  /**
   * @Route(path="/feedback/add", name="app_insurancecompany_feedback_new")
   */
  public function newAction(Request $request)
  {
    $feedback = new Feedback();
    $feedback->setAuthor($this->getUser());

    if ($request->get('letter'))
    {
      $feedback->setReviewLetter(true);
    }

    $form = $this->createForm(FeedbackType::class, $feedback, [
      'csrf_protection' => false,
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $em = $this->getDoctrine()->getManager();

      $em->persist($feedback);
      $em->flush();

      try
      {
        $mainMail = $this->mainMail->getMainMail($request);
        if ($mainMail)
        {
          $this->sendNewFeedback($feedback, $mainMail);
        }
        else
        {
          $this->get('logger')->warn('Unable to send notification about new review to site administrator. Administrator email is not set');
        }
      }
      catch (BitrixRequestException $e)
      {
        $this->get('logger')->warn('Unable to send notification about new review to site administrator. Could not resolve administrator email: ' . $e);
      }
      catch (\Exception $e2)
      {
        $this->get('logger')->warn('Unable to send notification about new review to site administrator: ' . $e2);
      }

      $this->addFlash('magnific', 'Спасибо за отзыв! Он будет опубликован после модерации.');

      return $this->redirectToRoute('app_insurancecompany_feedback_index');
    }


    return $this->render('InsuranceCompany/Review/new.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route(path="/feedback/remove", name="app_insurancecompany_feedback_remove")
   */
  public function removeAction(Request $request)
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $em = $this->getDoctrine()->getManager();

    $feedback = $em->getRepository(Feedback::class)
      ->find($request->get('id'));
    if ($feedback)
    {
      $em->remove($feedback);
      $em->flush();

      return new JsonResponse(1);
    }

    return new JsonResponse([
      //errors
    ], 400);
  }

  /**
   * @Route(path="/feedback/add-comment", name="add_comment")
   */
  public function addCommentAction(Request $request, UserInterface $user = null)
  {
    $data = $request->request->all();

    /**
     * @var Comment $comment
     */
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

      $feedback = $comment->getFeedback();
      $feedback->setUpdatedAt(new \DateTime());

      $em = $this->getDoctrine()->getManager();
      $em->persist($comment);
      $em->persist($feedback);
      $em->flush();
    }

    return $this->redirectToRoute('app_insurancecompany_feedback_index', [], 302);
  }

  /**
   * @Route(path="/feedback/remove-comment", name="remove_comment_ajax")
   */
  public function removeCommentAction(Request $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $data = $request->request->all();
      $comment_id = isset($data['id']) ? $data['id'] : null;

      /**
       * @var Comment $comment
       */
      $comment = $this->getDoctrine()->getManager()->getRepository(Comment::class)
        ->findOneBy(['id' => $comment_id]);
      if (!empty($comment))
      {
        $em = $this->getDoctrine()->getEntityManager();
        foreach ($comment->getCitations() as $citation)
        {
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
    if ($request->isXmlHttpRequest())
    {
      $data = $request->request->all();
      $comment_id = isset($data['id_comment']) ? $data['id_comment'] : null;
      $message = isset($data['message']) ? $data['message'] : null;

      $comment = $this->getDoctrine()->getManager()->getRepository(Comment::class)
        ->findOneBy(['id' => $comment_id]);
      if (!empty($comment))
      {
        $userId = null !== $user ? $user->getId() : null;
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)
          ->findOneBy(['id' => $userId]);

        $representative = false;
        if ($user->getRepresentative())
        {
          $feedback = $comment->getFeedback();
          if (!empty($feedback))
          {
            $branch = $feedback->getBranch();
            if (!empty($branch) && !empty($user->getBranch()) && $user->getBranch()->getId() === $branch->getId())
            {
              $representative = true;
            }
          }
        }

        /**
         * @var Citation $citation
         */
        $citation = new Citation();
        $citation->setUser($user);
        $citation->setComment($comment);
        $citation->setText($message);
        $citation->setRepresentative($representative);
        $citation->setCreatedAt(new \DateTime());
        $citation->setUpdatedAt(new \DateTime());

        $comment = $citation->getComment();
        $feedback = $comment->getFeedback();
        $feedback->setUpdatedAt(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($feedback);
        $em->persist($citation);
        $em->flush();
      }
    }

    return new JsonResponse(1);
  }

  /**
   * @Route(path="/feedback/remove-citation", name="remove_citation_ajax")
   */
  public function removeCitationAction(Request $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $data = $request->request->all();
      $citation_id = isset($data['id']) ? $data['id'] : null;

      $citation = $this->getDoctrine()->getManager()->getRepository(Citation::class)
        ->findOneBy(['id' => $citation_id]);
      if (!empty($citation))
      {
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
    if ($request->isXmlHttpRequest())
    {
      $data = $request->request->all();
      $region_id = isset($data['region_id']) ? $data['region_id'] : null;

      $branches = $this->getDoctrine()->getManager()->getRepository(CompanyBranch::class)
        ->findBy([
          'region' => $region_id
        ]);
      $content = '';
      foreach ($branches as $branch)
      {
        $content .= '<li value="' . $branch->getId() . '" class="custom-serach__items_item hospital company-select-item
                      data-kpp="' . $branch->getKpp() . '">' .
          $branch->getName() . '</li>';
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
    if ($request->isXmlHttpRequest())
    {
      $data = $request->request->all();
      $id = !empty($data['id']) ? $data['id'] : null;
      $accepted = !empty($data['accepted']) ? $data['accepted'] : null;
      $reject = !empty($data['reject']) ? $data['reject'] : null;
      $status = !empty($accepted) ?
        FeedbackModerationStatus::MODERATION_ACCEPTED :
        (!empty($reject) ? FeedbackModerationStatus::MODERATION_REJECTED : FeedbackModerationStatus::MODERATION_NONE);

      /**
       * @var Feedback $feedback
       */
      $feedback = $this->getDoctrine()->getManager()->getRepository(Feedback::class)
        ->findOneBy(['id' => $id]);
      if (!empty($feedback))
      {
        $feedback->setModerationStatus($status);
        $this->getDoctrine()->getManager()->persist($feedback);
        $this->getDoctrine()->getManager()->flush();

        $branch = $feedback->getBranch();
        $emails = [];
        if (!empty($branch->getEmailFirst()))
        {
          $emails[] = $branch->getEmailFirst();
        }
        if (!empty($branch->getEmailSecond()))
        {
          $emails[] = $branch->getEmailSecond();
        }
        if (!empty($branch->getEmailThird()))
        {
          $emails[] = $branch->getEmailThird();
        }

        foreach ($emails as $email)
        {
          try
          {
            $this->sendNewFeedback($feedback, $email);
          } catch (\Exception $e)
          {
            $this->get('logger')->warn('Unable to send notification about new review to insurance branch: ' . $e);
          }
        }
      }

      return new JsonResponse(1);
    }
  }

  /**
   * @param $emailTo
   * @param $url
   * @param $date
   */
  private function sendNewFeedback(Feedback $feedback, $emailTo)
  {
    $url = $this->generateUrl('app_insurancecompany_feedback_show', [
      'id' => $feedback->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
    $date = $feedback->getCreatedAt()->format('Y-m-d H:i:s');

    $message = (new \Swift_Message('Новый отзыв'))
      ->setFrom($this->container->getParameter('mailer_from'))
      ->setTo($emailTo)
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
  }
}
