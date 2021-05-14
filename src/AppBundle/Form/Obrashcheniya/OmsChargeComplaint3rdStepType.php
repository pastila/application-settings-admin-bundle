<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Form\Obrashcheniya;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class OmsChargeComplaint3rdStepType extends OmsChargeComplaintType
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
      ->add('urgent', ChoiceType::class, [
        'expanded' => true,
        'required' => true,
        'choices' => [
          'Плановое' => '0',
          'Неотложное' => '1',
        ],
      ]);
  }

  public function finishView (FormView $view, FormInterface $form, array $options)
  {
    parent::finishView($view, $form, $options);
    $view['urgent'][0]->vars['help'] = 'Не связанное с ухудшением здоровья. Прохождение очередного обследования по хроническому заболеванию для контроля состояния и лечения. При этом важно, что ухудшения состояния нет. Также сюда относятся профилактические осмотры, диспансеризация.';
    $view['urgent'][1]->vars['help'] = 'Любое обращение вызванное ухудшением состояния здоровья. Ухудшение может быть связано с хроническим заболеванием (обострение) или с острым (например, ОРВИ). Не важно, когда произошло ухудшение - день или неделю назад.';
  }
}
