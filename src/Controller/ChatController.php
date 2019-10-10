<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MercureCookieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(MercureCookieGenerator $mercureCookieGenerator)
    {
        $response = $this->render('chat/index.html.twig', [
            'config' => [
                'topic' => 'chat',
                'publishRoute' => $this->generateUrl('publisher', ['topic' => 'chat'])
            ]
        ]);

        $response->headers->set('set-cookie', $mercureCookieGenerator->generate($this->getUser()));
        return $response;
    }

    /**
     * @Route("/chat/message/{user}", name="postMessage", methods={"POST"})
     */
    public function postMessage(Publisher $publisher, ?User $user = null)
    {
        $targets = [];
        if ($user !== null) {
            $targets = ["http://o-stan.fr/user/{$user->getId()}"];
        }

        $update = new Update("http://o-stan.fr/chat", "[]", $targets);
        $publisher($update);

        return $this->render('chat/index.html.twig', [
            'config' => [
                'topic' => 'http://o-stan.fr/chat',
                'publishRoute' => $this->generateUrl('publisher', ['topic' => 'http://o-stan.fr/chat'])
            ]
        ]);
    }
}
