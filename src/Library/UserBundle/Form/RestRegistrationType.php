<?php
namespace Library\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

class RestRegistrationType extends AbstractType
{
    protected $routeName;
    private $class;

    /**
     * @param Container $container
     * @param string $class The User class name
     */
    public function __construct(Container $container, $class)
    {
        $request = $container->get('request');
        $this->routeName = $request->get('_route');
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email')
            ->add('username', null)
            ->add('plainPassword', 'password');

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->setDefaults(array(
            'data_class'      => $this->class,
            'intention'       => 'registration',
            'csrf_protection' => false
        ));

    }

    public function getName()
    {
        return 'fos_user_rest_registration';
    }
}