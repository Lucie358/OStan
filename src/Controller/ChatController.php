<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\Slugger;
use App\Entity\Message;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Symfony\Component\Mercure\Update;
use App\Service\MercureCookieGenerator;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(UserRepository $userRepository, Request $request, MessageRepository $messageRepository, Slugger $slugger)
    {
		$users = $userRepository->findAll(); 
		
		
			$currentUser =  $this->getUser();
			$conversations = $messageRepository->findByConversation($currentUser);			
			
			
		
		
		dump($conversations);
		
		


        return $this->render('chat/index.html.twig', [
            //    'messages' => $messages, 
            //    'form' => $form->createView()
			'users' => $users,
			'conversations'=>$conversations,
			'currentUser'=>$currentUser


        ]);
    }

    /**
     * @Route("/chat/message/{user}", name="postMessage", methods={"POST"})
     */
    public function postMessage(Request $request, Publisher $publisher, ?User $user = null)
    {
        $currentUser =  $this->getUser();
        $message = $request->get("message");

        $targets = [];
        if ($user !== null) {
            $targets = [
                "http://o-stan.fr/user/{$user->getId()}",
                "http://o-stan.fr/user/{$currentUser->getId()}"
            ];
        }

        $newMessage = new Message();
        $newMessage->setUser($currentUser);
        $newMessage->setUserReceiver($user);
        $newMessage->setContent($message);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newMessage);
        $entityManager->flush();

        $eventData = [
        
            'message' => $message,
            'from' => [
                'id' => $currentUser->getId(),
                'username' => $currentUser->getUsername()
            ]
        ];

        $update = new Update("http://o-stan.fr/chat", json_encode($eventData), $targets);
        $publisher($update);

        

        //On construit manuellement la réponse envoyée au navigateur (pas réussi à utiliser le module sérializer pour transformer un objet en Json)
        $toReturn = [
            'message' => $message,
        ];
        //On construit une réponse json grâce à notre tableau fait-main toReturn
        $response = new JsonResponse($toReturn);
        //On l'envoie au navigateur, on peut les voir dans Network du devtool
        return $response;
    }
    
    /**
     * @Route("/chat/{id}", name="showmessagechat")
     */
    public function show(User $user, Request $request, MessageRepository $messageRepository,  MercureCookieGenerator $mercureCookieGenerator)
    {


        $currentUser =  $this->getUser();
        $messages = $messageRepository->findConversation($currentUser, $user);
       


        $response = $this->render('chat/show.html.twig', [
            'messages' => $messages,
            'receiver' => $user
         
        ]);

        $response->headers->set('set-cookie', $mercureCookieGenerator->generate($this->getUser()));
        return $response;
    }
}
