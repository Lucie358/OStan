<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Lastname', TextType::class,[
				'label' => 'Nom'
			])
            ->add('Firstname', TextType::class,[
				'label' => 'Prénom'
			])
            ->add('Object', TextType::class,[
				'label' => 'Objet du message'
			])
            ->add('Mail', EmailType::class,[
				'label' => 'Adresse mail'
			])
            ->add('Message', TextareaType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
