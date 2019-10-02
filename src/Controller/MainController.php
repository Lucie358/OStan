<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;



class MainController extends AbstractController
{
   

     /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function legalMentions()
    {
        return $this->render('main/cgu.html.twig', [
        ]);
    }

      /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {

            $form = $this->createForm(ContactType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $userMail= $form->get('Mail')->getData();
                $userFirstname = $form->get('Firstname')->getData();
                $userLastname = $form->get('Lastname')->getData();
                $userMessage = $form->get('Message')->getData();
                $userObject = $form->get('Object')->getData();



                $message = (new \Swift_Message($userObject))
                ->setFrom($userMail)
                ->setTo('ostan.contact@gmail.com');

                $message->setBody(
                    $this->renderView(
                        // templates/emails/contactAdmin.html.twig
                        'emails/contactAdmin.html.twig',
                        ['firstname' => $userFirstname,
                        'lastname'=>$userLastname,
                        'message'=>$userMessage,
                        'mail'=>$userMail]
                    ),
                    'text/html'
                );
                $mailer->send($message);

                return $this->redirectToRoute('advice_post');
            }


        return $this->render('main/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/plan-du-site/", name="plan_du_site")
     */
    public function sitemap()
    {

        return $this->render('main/plan_du_site.html.twig', [
        ]);
    }

    /**
     * @Route("/le-concept/", name="concept")
     */
     public function concept()
     {


         return $this->render('main/concept.html.twig', [
         ]);
     }


}
