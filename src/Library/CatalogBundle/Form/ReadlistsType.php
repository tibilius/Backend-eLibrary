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
            ->add('name', 'text')
            ->add('color', 'text')
            ->add('type', 'choice', ['choices' => ReadlistEnumType::getChoices()])
            ->add('user', 'entity', [
                'data_class' => 'Library\UserBundle\Entity\User',
                'property' => 'id',
            ])
            ->add('book', 'entity', [
                'data_class' => 'Library\CatalogBundle\Entity\Books',
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
            'data_class' => 'Library\CatalogBundle\Entity\Readlists'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'library_catalogbundle_readlists';
    }
}
