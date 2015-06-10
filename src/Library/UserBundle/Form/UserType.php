<?php

namespace Library\UserBundle\Form;

use Library\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', ['required' => false])
            ->add('plain_password', 'text', ['required' => false])
            ->add('firstName', 'text', ['required' => true])
            ->add('lastName', 'text', ['required' => true])
            ->add('middleName', 'text', ['required' => false])
            ->add('avatarImage', 'file', ['required' => false])
            ->add('email', 'email', ['required' => true])
            ->add('roles', 'choice', [
                'choices'  => User::getUserRoles(),
                'multiple' => true,
                'required' => true,
            ]);
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
        return 'library_user_edit';
    }
}
