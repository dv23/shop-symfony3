<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ProductType extends AbstractType
{
	public function buildForm(FormBuilderInterface $Builder,  array $options){
	$Builder->add('name')->add('presentation')->add('price')->add('stock')->add('techs')->add('details')->add('url')
	->add('category')
	//->add('category', EntityType::class, array('class' => 'AppBundle:Category',
	//'choice_label' => 'name', 
	//'choice_value' => 'url'
	//	))
	//->add('authorEmail', EmailType::class)
	//->add('image', MediaType::class) // Ajoute image en sous formulaire 'multiple' => 'true'
	//->add('media', EntityType::class, array('class' => 'AppBundle:Media','choice_label' => 'alt'))
	//->add('media', EntityType::class, array('class' => 'AppBundle:Media','query_builder' => function (EntityRepository $er) {
    //    return $er->createQueryBuilder('u')
    //        ->orderBy('u.alt', 'ASC');
    //}, 'choice_label' => 'alt'))
	->add('save', SubmitType::class);
	}
}

?>