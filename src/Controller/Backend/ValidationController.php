<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/", name="backend_")
 */
class ValidationController extends AbstractController
{
    /**
     * @Route("validation", name="validation")
     */
    public function validationList(UserRepository $userRepository)
    { 
        $editors = $userRepository->findEditors();
        dump($editors);
        return $this->render('backend/validation/index.html.twig', [
            'editors' => $editors,
            // 'formSearchUser' => $formSearchUser->createView(),
        ]);

    }

    /**
     * @Route("validate/{id}", name="validate")
     */
    public function validate(User $user){
        $user = $user->setIsActive(true);
        //On met à jour en base
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('backend_validation');


        

    }
}
