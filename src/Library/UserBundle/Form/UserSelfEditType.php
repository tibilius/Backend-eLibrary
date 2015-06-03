<?php

namespace Library\UserBundle\Form;

use Library\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserSelfEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', ['required' => false, 'empty_data' => null])
            ->add('firstName', 'text', ['required' => false, 'empty_data' => null])
            ->add('lastName', 'text', ['required' => false, 'empty_data' => null])
            ->add('middleName', 'text', ['required' => false, 'empty_data' => null])
            ->add('avatarImage', 'file', ['required' => false, 'empty_data' => null])
            ->add('email', 'email', ['required' => false, 'empty_data' => null]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Library\UserBundle\Entity\User',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'library_user_self_update';
    }
}
