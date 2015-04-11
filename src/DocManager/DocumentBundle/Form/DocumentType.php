<?php
/**
 * Created by PhpStorm.
 * User: damien
 * Date: 06/03/15
 * Time: 14:31
 */
namespace DocManager\DocumentBundle\Form;
use DocManager\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
            ->add('description', 'ckeditor')
            ->add('documentDate', 'date',array(
                'input' => 'datetime',
                'widget' => 'single_text'
            ))
            ->add('file', 'file')
            ->add('categories', 'collection', array(
                'type' => new CategoryType(),
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('save', 'submit');
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event){
                if($this->user->hasRole('ROLE_PREMIUM'))
                {
                    $event->getForm()->add('expirationDate', 'date',array(
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => false
                    ));
                }
                else
                {
                    $event->getForm()->add('expirationDate', 'date',array(
                        'input' => 'datetime',
                        'widget' => 'single_text'
                    ));
                }
            });
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
