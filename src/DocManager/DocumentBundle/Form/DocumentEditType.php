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

class DocumentEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('expirationDate')
            ->remove('file')
            ->remove('categories');
    }

    public function getParent()
    {
        return new DocumentType();
    }

    public function getName()
    {
        return 'doc_manager_documentbundle_edit_document';
    }

}
