<?php


namespace AppBundle\Admin\Common;

use Accurateweb\NewsBundle\Admin\NewsAdmin as Base;
use AppBundle\Entity\Common\Article;
use AppBundle\Form\Common\TinyMceType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class ArticleAdmin extends Base
{
  const CROP_THUMB_MAX_WIDTH = 150;
  const CROP_THUMB_MAX_HEIGHT = 100;
  protected $translationDomain = 'messages';

  public function toString($object)
  {
    $s = null;
    if ($object instanceof Article)
    {
      if (mb_strlen($object->getTitle(), 'UTF-8') > 35)
      {
        $s = mb_substr($object->getTitle(), 0, 32, 'UTF-8') . '...';
      } else
      {
        $s = $object->getTitle();
      }
    }
    return $s;
  }

  /**
   * @param DatagridMapper $datagridMapper
   */
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title');
  }

  public function configureFormFields(FormMapper $form)
  {
    $form
      ->tab("Основные")
      ->add("title")
      ->add('slug', TextType::class, [
        'help' => 'Если оставить пустым, то будет сгенерирован автоматически.',
        'required' => false,
        'constraints' => [
          new Length([
            'max' => 255,
          ]),
        ],
      ])
      ->add("announce", TextareaType::class)
      ->add('text', TinyMceType::class, [
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
      ->end();
  }

  /**
   * @param ShowMapper $showMapper
   */
  protected function configureShowFields(ShowMapper $showMapper)
  {
    $showMapper
      ->add('title')
      ->add('text');
  }
}