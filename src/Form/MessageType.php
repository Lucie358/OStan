<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Post;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class MessageType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // récupérer l'url
        $url = ($_SERVER["REQUEST_URI"]);

        

        // construit un formulaire différent selon l'url, à partir de la meme Entité
        if($url == '/chat'){
        
            $builder
            ->add('title',null,array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Titre...'
                )
            ))
                ->add('content', TextareaType::class ,array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Message...'
                    )
                ))

                // pour définir, avec menu déroulant, le destinataire du message
                
                ->add('userReceiver', EntityType::class,[
                    'label' => false,
                    'class'=> User::class,
                    'expanded' =>false,
                    'multiple' =>false,
                    'query_builder' => function (UserRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.username', 'ASC');
                    },
                    
                ])
                


                
            ;



         }else {
             $builder

            
                ->add('content', TextareaType::class ,array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Votre message'
                        
                    )
                    ));
                
                 
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
