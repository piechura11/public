<?php

namespace LinkerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LinkForm extends AbstractType{
	 public function buildForm(FormBuilderInterface $builder, array $options)
	 {
        $builder
        ->add('longLink', 'textarea', array('required' => 'true', 'label'=>'Wpisz link do skrócenia'))
        ->add('shortLink', 'text', array('required'=>false, 'label'=>'Nazwa krótkiego Url'))
        ->add('description', 'textarea', array('required'=>false, 'label'=>'Opis'))
        ->add('modyficator', 'choice', array( 'label'=>'Typ', 'data'=>0, 'choices' => array(
            0=>'Publiczny',
            1=>'Prywatny',
            2=>'Chroniony')))
        ->add('password', 'password', array('required'=>false, 'label'=>'Hasło'));


	 }

	 public function getName()
	 {
	 	return 'task';
	 }

}