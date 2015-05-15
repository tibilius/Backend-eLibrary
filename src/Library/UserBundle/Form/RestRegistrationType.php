<?php
namespace Library\UserBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

class RestRegistrationType extends RegistrationFormType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $required = ['required' => true];
        $notRequired = ['required' => false];
        $builder
            ->remove('username')
            ->add('firstName', 'text', $required)
            ->add('lastName', 'text', $required)
            ->add('middleName', 'text', $notRequired);

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Library\UserBundle\Entity\User',
            'intention'       => 'registration',
            'csrf_protection' => false
        ));

    }

    public function getName()
    {
        return 'fos_user_rest_registration';
    }
}