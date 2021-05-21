<?php

namespace AppBundle\Form\Obrashcheniya;

use AppBundle\Entity\OmsChargeComplaint\OmsChargeComplaintDocument;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OmsChargeComplaintDocumentType extends FileType implements DataTransformerInterface
{
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    $builder->addModelTransformer($this);
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    parent::configureOptions($resolver);
//    $resolver->setDefault('data_class', OmsChargeComplaintDocument::class);
  }

  public function transform ($value)
  {
    return $value;
  }

  public function reverseTransform ($value)
  {
    if (is_array($value))
    {
      $data = [];

      foreach ($value as $item)
      {
        $doc = new OmsChargeComplaintDocument();
        $doc->setFile($item);
        $data[] = $doc;
      }

      return $data;
    }

    return $value;
  }
}