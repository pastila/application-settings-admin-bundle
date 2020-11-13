<?php

namespace Accurateweb\GpnNewsBundle\Admin;

use Accurateweb\GpnNewsBundle\Model\NewsInterface;
use Accurateweb\MediaBundle\Form\ImageType;
use AppBundle\Form\Common\TinyMceType;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewsAdmin extends AbstractAdmin
{
  public function configureListFields(ListMapper $list)
  {
    $list
      ->add("title")
      ->add("createdAt", 'datetime', [
        'format' => 'd.m.Y H:i',
      ])
      ->add("publishedAt", 'datetime', [
        'format' => 'd.m.Y',
      ])
      ->add('_action', null, array(
        'actions' => array(
          'edit' => array(),
          'delete' => array(),
        )
      ));
  }

  public function configureFormFields(FormMapper $form)
  {
    $form
      ->tab("Основные")
        ->add("title")
        ->add("slug")
        ->add("announce",  TextareaType::class)
        ->add('text',  TinyMceType::class, [
            'attr' => [
              'class' => 'tinymce',
              'tinymce' => '{"theme":"simple"}',
              'data-theme' => 'bbcode',
            ],
            'required' => true
          ]
        )
        ->add("isPublished")
        ->add("publishedAt", DatePickerType::class, [
          'format' => 'd MMMM yyyy',
          'view_timezone' => 'UTC',
          'model_timezone' => 'UTC',
        ])
      ->end()
      ->end()
      ->tab("Изображение")
        ->add('teaser', 'Accurateweb\MediaBundle\Form\ImageType', [
          'required' => false,
          'label' => 'Изображение',
        ])
      ->end()
      ->end()
      ->tab('Внешние ссылки')
        ->add('isExternal')
        ->add('externalUrl')
      ->end()
      ->end();

    if ($this->getSubject())
    {
      $id = $this->getSubject()->getId();
      $form
        ->tab('Рекомендации')
        ->add('relatedNews', null, [
          "required" => false,
          'query_builder' => function (EntityRepository $er) use ($id)
          {
            if ($id)
            {
              return $er->createQueryBuilder('a')
                ->select('a')
                ->where('a.id <> :id')
                ->setParameter('id', $id);
            }
            return $er->createQueryBuilder('c')->select('c');
          }])
        ->end()
        ->end();
    }
  }

  /**
   * @param NewsInterface $object
   */
  public function prePersist($object)
  {
    if (!$object->getCreatedAt())
    {
      $object->setCreatedAt(new \DateTime());
    }

    $this->stripScripts($object);
  }

  public function preUpdate($object)
  {
    $this->stripScripts($object);
  }

  /**
   * Вырежет скрипты
   *
   * @param NewsInterface $news
   */
  private function stripScripts(NewsInterface $news)
  {
    $news->setTitle(preg_replace("/\<script\>.*\<\/script\>/u", '', $news->getTitle()));
    $news->setText(preg_replace("/\<script\>.*\<\/script\>/u", '', $news->getText()));
  }
}