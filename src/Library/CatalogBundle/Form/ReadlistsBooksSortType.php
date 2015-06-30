<?php

namespace Library\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReadlistsBooksSortType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('readlists', 'collection', [
                'type' => new ReadlistsBooksSortItemType(),
                'allow_add' => true,
            ]);
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Library\CatalogBundle\Entity\ReadlistsBooksSort',
            'csrf_protection' => false,
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'library_readlistbooks_sort';
    }
}
