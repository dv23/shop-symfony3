<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Symfony\Bridge\Doctrine\Form\Type\FileType;

class MediaType extends AbstractType
{
    /**
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class)
            ->add('alt')
            ->add('product', EntityType::class, array('class' => 'AppBundle:Product','choice_label' => 'name'))
            ->add('save', SubmitType::class)
        ;
    }

    /**
    * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => 'AppBundle\Entity\Media'
        ));
    }
}
