<?php

namespace AppBundle\Form\Common;

use AppBundle\Entity\Disease\CategoryDisease;
use AppBundle\Repository\Disease\CategoryDiseaseRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NestedTreeSuggestType
 * @package AppBundle\Form\Common
 */
class NestedTreeSuggestType extends ChoiceType implements DataTransformerInterface
{
  private $categoryDiseaseRepository;
  private $suggest;

  /**
   * NestedTreeSuggestType constructor.
   * @param CategoryDiseaseRepository $entityManager
   * @param ChoiceListFactoryInterface|null $choiceListFactory
   */
  public function __construct(CategoryDiseaseRepository $entityManager, ChoiceListFactoryInterface $choiceListFactory = null)
  {
    $this->suggest = [];
    $this->categoryDiseaseRepository = $entityManager;
    parent::__construct($choiceListFactory);
  }

  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->addModelTransformer($this);
    parent::buildForm($builder, $options);
  }

  /**
   * @param OptionsResolver $resolver
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
    $choices = [];

    foreach ($this->getSuggest() as $item)
    {
      $choices[$item->getName()] = $item->getId();
    }

    $resolver->setDefault('choices', $choices);
  }

  /**
   * @param mixed $value
   * @return int|mixed
   */
  public function transform($value)
  {
    if ($value instanceof CategoryDisease)
    {
      $value = $value->getId();
    }

    return $value;
  }

  /**
   * @param mixed $value
   * @return mixed|object|null
   */
  public function reverseTransform($value)
  {
    if (is_scalar($value))
    {
      $value = $this->categoryDiseaseRepository->find($value);
    }

    return $value;
  }

  /**
   * @return array
   */
  public function getSuggest()
  {
    $rootNodes = $this->categoryDiseaseRepository->getRootNodes();
    $this->getClientModelValues($rootNodes[0]);

    return $this->suggest;
  }

  /**
   * @param $section
   * @return array
   */
  public function getClientModelValues($section)
  {
    $values = array(
      'data' => (string)$section,
      'children' => $this->getChildCatalogSectionClientModelValues($section),
      'metadata' => array('id' => $section->getId())
    );

    return $values;
  }

  /**
   * @param $section
   * @return array
   */
  public function getChildCatalogSectionClientModelValues($section)
  {
    $children = array();

    $childSections = $section->getChildren();

    foreach ($childSections as $child)
    {
      if ($child->getTreeLevel() === CategoryDisease::LEVEL_SUBGROUP)
      {
        $this->suggest[] = $child;
      }
      $children[] = $this->getClientModelValues($child);
    }

    return $children;
  }
}