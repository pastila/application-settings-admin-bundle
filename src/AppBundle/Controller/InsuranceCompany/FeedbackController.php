<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Controller\InsuranceCompany;

use AppBundle\Entity\Company\Company;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\Company\FeedbackModerationStatus;
use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\User\User;
use AppBundle\Form\InsuranceCompany\FeedbackListFilterType;
use AppBundle\Model\InsuranceCompany\FeedbackListFilter;
use AppBundle\Model\InsuranceCompany\FeedbackListFilterUrlBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends Controller
{
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

    $user = new User();
    $user->setName('Максимов Николай');

    $rgn = new Region();
    $rgn->setName('03  Республика Бурятия');

    $fbc = new Company();
    $fbc->setName('СОГАЗ-МЕД');

    $fb1 = new Feedback();
    $fb1->setTitle('Мне помогли с лечением зуба даже без полиса');
    $fb1->setText('Оформлял полис ОМС в СОГАЗ, выдали временное свидетельство. Через 30 дней обещали выдать сам полис, но по какимто причинам полис задерживался. В этот период мне пришлось пойти в стоматологическую поликлинику с острой болью, но свидетельство было уже не действительно так как у него ограничен срок действия. Пришлось из больницы звонить в страховую компанию и я уже думал что лечение мне по полису не светит. Но страховая уж не знаю как но уговорила врачей принять по свидетеьству меня, вылечили бесплатно. Я считаю что свою работу они сделали. Хотя задерживать полис все равно неправильно, но мне страховая по телефону объяснила, что это не их вина и полис задерживается в г. Москве. Не знаю так ли?');
    $fb1->setValuation(5);
    $fb1->setCompany($fbc);
    $fb1->setUser($user);
    $fb1->setRegion($rgn);
    $fb1->setModerationStatus(FeedbackModerationStatus::MODERATION_NONE);


    $fb2 = new Feedback();
    $fb2->setTitle('Мне помогли с лечением зуба даже без полиса');
    $fb2->setText('Оформлял полис ОМС в СОГАЗ, выдали временное свидетельство. Через 30 дней обещали выдать сам полис, но по какимто причинам полис задерживался. В этот период мне пришлось пойти в стоматологическую поликлинику с острой болью, но свидетельство было уже не действительно так как у него ограничен срок действия. Пришлось из больницы звонить в страховую компанию и я уже думал что лечение мне по полису не светит. Но страховая уж не знаю как но уговорила врачей принять по свидетеьству меня, вылечили бесплатно. Я считаю что свою работу они сделали. Хотя задерживать полис все равно неправильно, но мне страховая по телефону объяснила, что это не их вина и полис задерживается в г. Москве. Не знаю так ли?');
    $fb2->setValuation(3);
    $fb2->setCompany($fbc);
    $fb2->setUser($user);
    $fb2->setRegion($rgn);
    $fb2->setModerationStatus(FeedbackModerationStatus::MODERATION_ACCEPTED);

    $fb3 = new Feedback();
    $fb3->setTitle('Мне помогли с лечением зуба даже без полиса');
    $fb3->setText('Оформлял полис ОМС в СОГАЗ, выдали временное свидетельство. Через 30 дней обещали выдать сам полис, но по какимто причинам полис задерживался. В этот период мне пришлось пойти в стоматологическую поликлинику с острой болью, но свидетельство было уже не действительно так как у него ограничен срок действия. Пришлось из больницы звонить в страховую компанию и я уже думал что лечение мне по полису не светит. Но страховая уж не знаю как но уговорила врачей принять по свидетеьству меня, вылечили бесплатно. Я считаю что свою работу они сделали. Хотя задерживать полис все равно неправильно, но мне страховая по телефону объяснила, что это не их вина и полис задерживается в г. Москве. Не знаю так ли?');
    $fb3->setValuation(3);
    $fb3->setCompany($fbc);
    $fb3->setUser($user);
    $fb3->setRegion($rgn);
    $fb3->setModerationStatus(FeedbackModerationStatus::MODERATION_REJECTED);

    $reviewListFilter = new FeedbackListFilter();

    $reviewListFilterForm = $this->createForm(FeedbackListFilterType::class, $reviewListFilter, [
      'url_builder' => new FeedbackListFilterUrlBuilder($reviewListFilter, $this->get('router'))
    ]);
    $reviewListFilterForm->submit($request->query->get($reviewListFilterForm->getName()));

//      $qb = $this
//        ->getDoctrine()
//        ->getManager()
//        ->getRepository('AppBundle:Company\CompanyFeedback')
//        ->createQueryBuilder('fb');
//
//      $paginator = new Paginator($qb);

//    $companies = $this->getDoctrine()->getRepository('AppBundle:Company\Company')->findAll();

    $nbReviews = 1;

    return $this->render('InsuranceCompany/Review/list.html.twig', [
      'reviews' => [
        $fb1, $fb2, $fb3
      ],
      'nbReviews' => $nbReviews,
      'filter' => $reviewListFilter,
      'filterForm' => $reviewListFilterForm->createView()
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
