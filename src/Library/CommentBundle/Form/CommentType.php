<?php

namespace Library\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends \FOS\CommentBundle\Form\CommentType
{
    public function __construct($commentClass)
    {
        parent::__construct($commentClass); // TODO: Change the autogenerated stub
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Library\CommentBundle\Entity\Comment',
            'csrf_protection' => false,

        ));
    }

    public function getName()
    {
        return 'library_commentbundle_comment';
    }
}
