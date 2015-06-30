<?php

namespace Library\CatalogBundle\Form;

use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReadlistsBooksType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('fact', 'integer')
            ->add('plan', 'integer')
            ->add('book', 'entity', [
                'class' => 'Library\CatalogBundle\Entity\Books',
                'property' => 'id',
                'required' => true
            ])
            ->add('readlist', 'entity', [
                'class' => 'Library\CatalogBundle\Entity\Readlists',
                'property' => 'id',
                'required' => true
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Library\CatalogBundle\Entity\ReadlistsBooks',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'library_catalog_readlist_books';
    }
}
