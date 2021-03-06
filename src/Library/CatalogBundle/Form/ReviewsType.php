<?php

namespace Library\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewsType extends AbstractType
{
    protected $maxSymbols;

    public function __construct($maxSymbols)
    {
        $this->maxSymbols = $maxSymbols;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text', ['required' => true])
            ->add('text','textarea', ['max_length' => $this->maxSymbols, 'required' => true])
            ->add('book', 'entity', [
                'class' => 'Library\CatalogBundle\Entity\Books',
                'property' => 'id',
                'required' => false,
            ])
            ->add('thread', 'entity', [
                'class' => 'Library\CommentBundle\Entity\Thread',
                'property' => 'id',
                'required' => false,
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Library\CatalogBundle\Entity\Reviews',
            'csrf_protection'   => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'library_reviews';
    }
}
