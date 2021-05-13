<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;

use AppBundle\Entity\Geo\Region;
use AppBundle\Entity\Organization\MedicalOrganization;
use AppBundle\Repository\Geo\RegionRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class OmsChargeComplaint2ndStepType extends OmsChargeComplaintType
{
//  private $regionRepository;
//
//  public function __construct(RegionRepository $regionRepository)
//  {
//    $this->regionRepository = $regionRepository;
//  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('medicalOrganization', EntityType::class, [
        'class' => MedicalOrganization::class,
        'required' => true,
        'constraints' => [
          new NotNull([
            'message' => 'Выберите медицинскую организацию',
          ]),
        ],
        'query_builder' => function(EntityRepository $entityRepository) use($options) {
          return $entityRepository
            ->createQueryBuilder('o')
            ->join('o.years', 'y')
            ->where('y.year = :year')
            ->andWhere('o.region = :region')
            ->setParameters([
              'year' => $options['year'],
              'region' => $options['region'],
            ])
            ->orderBy('o.name');
        },
      ]);
  }

  public function configureOptions (OptionsResolver $resolver)
  {
    $resolver->setRequired('year');
    $resolver->setRequired('region');
    $resolver->setAllowedTypes('year', 'int');
    $resolver->setAllowedTypes('region', Region::class);
  }
}
