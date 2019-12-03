<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Utils\Slugger;
use App\Entity\Message;
use App\Repository\ConversationRepository;
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
	public function index(UserRepository $userRepository, Request $request, ConversationRepository $conversationRepository, Slugger $slugger)
	{
		$users = $userRepository->findAll();

		$currentUser =  $this->getUser();
		$conversations = $conversationRepository->findConversationsByOneUser($currentUser);

		return $this->render('chat/index.html.twig', [
			//    'messages' => $messages, 
			//    'form' => $form->createView()
			'users' => $users,
			'conversations' => $conversations,
			'currentUser' => $currentUser
		]);
	}

	/**
	 * @Route("/chat/message/{conversation}", name="postMessage", methods={"POST"})
	 */
	public function postMessage(Request $request, Publisher $publisher, ?Conversation $conversation = null)
	{
		$currentUser =  $this->getUser();
		$message = $request->get("message");
		$targets = [];
		if ($conversation !== null) {
			// $targets = [
			//     "http://o-stan.fr/user/{$conversation->getId()}",
			// ];
			$targets = [];
			foreach ($conversation->getUsers() as $user) {
				$targets[] = "http://localhost/user/{$user->getId()}";
			}
		}


		$newMessage = new Message();
		$newMessage->setUser($currentUser);
		$newMessage->setContent($message);
		$newMessage->setConversation($conversation);
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($newMessage);
		$entityManager->flush();

		$eventData = [
			'message' => $message,
			'from' => [
				'id' => $currentUser->getId(),
				'username' => $currentUser->getUsername(),
			],
			'date'=> $newMessage->getCreatedAt(),
			'conversation' => [
				'id' => $conversation->getId(),
			]
		];

		$update = new Update("http://localhost/chat", json_encode($eventData), $targets);
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
	 * @Route("/chat/newConversation", name="newConversation")
	 */
	public function newConversation(Publisher $publisher, Request $request, ConversationRepository $conversationRepository, UserRepository $userRepository)
	{

		$userReceiverId = $request->get('receiverId');
		$userReceiver = $userRepository->find($userReceiverId);
		$currentUser =  $this->getUser();
		$conversationToRedirect = $conversationRepository->findByUsers(array($currentUser, $userReceiver));

		if ($conversationToRedirect == null) {
			$conversationToRedirect = new Conversation();
			$conversationToRedirect->addUser($currentUser);
			$conversationToRedirect->addUser($userReceiver);
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($conversationToRedirect);

			$entityManager->flush();
		}

		$message = $request->get("messageContent");

		$targets = [
			"http://localhost/user/{$currentUser->getId()}",
			"http://localhost/user/{$userReceiver->getId()}"
		];

		$newMessage = new Message();
		$newMessage->setUser($currentUser);
		$newMessage->setContent($message);
		$newMessage->setConversation($conversationToRedirect);

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($newMessage);
		$entityManager->flush();

		$eventData = [
			'message' => $message,
			'from' => [
				'id' => $currentUser->getId(),
				'username' => $currentUser->getUsername(),
			],
			'conversation' => [
				'id' => $conversationToRedirect->getId(),
			]
		];
		$update = new Update("http://localhost/chat", json_encode($eventData), $targets);
		$publisher($update);
		return $this->redirectToRoute('showmessagechat', array('id' => $conversationToRedirect->getId()));
	}

	/**
	 * @Route("/chat/{id}", name="showmessagechat")
	 */
	public function show(Conversation $conversation, MessageRepository $messageRepository, MercureCookieGenerator $mercureCookieGenerator)
	{
		$messages = $messageRepository->findByConversation($conversation);
		$currentUser =  $this->getUser();
		$response = $this->render('chat/show.html.twig', [
			'messages' => $messages,
			'currentUser' => $currentUser,
			'conversation' => $conversation
		]);
		$response->headers->set('set-cookie', $mercureCookieGenerator->generate($this->getUser()));
		return $response;
	}
}
