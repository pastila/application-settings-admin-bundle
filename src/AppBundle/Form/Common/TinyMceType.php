<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class TinyMceType
 * @package AppBundle\Form\Common
 */
class TinyMceType extends TextareaType
{
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = array_replace_recursive($view->vars['attr'], [
            'class' => 'tinymce',
        ]);
    }
}
