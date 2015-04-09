<?php
/**
 * Created by PhpStorm.
 * User: damien
 * Date: 09/04/15
 * Time: 10:26
 */
namespace DocManager\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CkeditorType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('class' => 'ckeditor') // On ajoute la classe
        ));
    }

    public function getParent() // On utilise l'héritage de formulaire
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'ckeditor';
    }
}