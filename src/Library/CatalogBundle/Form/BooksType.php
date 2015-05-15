<?php

namespace Library\CatalogBundle\Form;

use Library\CommentBundle\Form\ThreadType;
use Library\VotesBundle\Form\RatingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BooksType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', ['required' => true])
            ->add('description', 'text')
            ->add('isbn', 'text')
            ->add('pageNumber', 'integer', ['required' => true])
            ->add('writers', 'entity', [
                'class'    => 'Library\CatalogBundle\Entity\Writers',
                'required' => true,
                'multiple' => true,
                'property' => 'id',
            ])
            ->add('thread', 'entity', [
                'class'    => 'Library\CommentBundle\Entity\Thread',
                'required' => false,
                'property' => 'id',
            ])
            ->add('rating', 'entity', [
                'class'    => 'Library\VotesBundle\Entity\Rating',
                'property' => 'id',
                'required' => false,
            ])
            ->add(
                'categories', 'entity', [
                    'class'    => 'Library\CatalogBundle\Entity\Categories',
                    'required' => false,
                    'multiple' => true,
                    'property' => 'id',
                ]
            )
            ->add('readlists', 'entity', [
                'class'    => 'Library\CatalogBundle\Entity\Readlists',
                'required' => false,
                'property' => 'id',
            ])
            ->add('file', 'file');
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Library\CatalogBundle\Entity\Books',
            'csrf_protection' => false,
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'library_books';
    }
}
