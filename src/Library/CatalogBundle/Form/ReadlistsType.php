<?php

namespace Library\CatalogBundle\Form;

use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReadlistsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', ['max_length'=> 1024])
            ->add('color', 'text', ['max_length'=> 50])
            ->add('type', 'choice', [
                'choices' => ReadlistEnumType::getAllowedChoices(),
                'required' => true,
                'empty_data'  => ReadlistEnumType::USUAL,
            ])
            ->add('books', 'entity', [
                'class' => 'Library\CatalogBundle\Entity\Books',
                'required' => false,
                'multiple' => true,
                'property' => 'id',
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Library\CatalogBundle\Entity\Readlists',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'library_catalog_readlists';
    }
}
