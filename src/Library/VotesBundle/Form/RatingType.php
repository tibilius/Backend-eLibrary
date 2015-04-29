<?php

namespace Library\VotesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RatingType extends AbstractType
{

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Library\VotesBundle\Entity\Rating'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'library_votesbundle_rating';
    }
}
