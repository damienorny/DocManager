<?php
/**
 * Created by PhpStorm.
 * User: damien
 * Date: 06/03/15
 * Time: 14:31
 */
namespace DocManager\DocumentBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
            ->add('description', 'textarea')
            ->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DocManager\DocumentBundle\Entity\Document'
        ));
    }

    public function getName()
    {
        return 'doc_manager_documentbundle_document';
    }

}
