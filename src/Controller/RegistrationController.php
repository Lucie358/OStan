<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\Slugger;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use App\Repository\StatusRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(StatusRepository $statusRepository, Request $request, GuardAuthenticatorHandler $guardHandler, UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $authenticator, RoleRepository $roleRepository, Slugger $slugger, \Swift_Mailer $mailer): Response
    {
        $code = 'ROLE_USER_USER';
        $defaultRole = $roleRepository->findOneByCode($code);
        


        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        // if ($user->getEmail()) {
        //     dump($user);
        //     die;
        // }

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $user->getAvatar();

            if (!is_null($user->getAvatar())) {
                //je genere un nom de fichier unique pour eviter d'ecraser un fichier du meme nom & je concatene avec l'extension du fichier d'origine
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

                try {
                    //je deplace mon fichier dans le dossier souhaité
                    $file->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    dump($e);
                }
                $user->setAvatar($fileName);
            }


            $user->setRole($defaultRole);
            foreach ($user->getJobs() as $job) {
                if ($job->getName() == "Editeur") {
                    $user->setIsActive(false);
                } else {
                    $user->setIsActive(true);
                }

                # code...
            }

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $slug = $slugger->slugify($user->getUsername());
            $user->setSlug($slug);
            $user->setIsAccountNonLocked(true);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email


            if ($user->getIsActive() == true) {
                $userEmail = $user->getEmail();
                $username = $user->getUsername();

                $message = (new \Swift_Message('Bienvenue sur O\'Stan !'))
                    ->setFrom(['ostan.contact@gmail.com' => 'O\'Stan'])
                    ->setTo($userEmail);

                $message->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        ['username' => $username]
                    ),
                    'text/html'
                );
                $mailer->send($message);

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            } else {
                $this->addFlash(
                    'editorregister',
                    'Merci pour votre inscription ! En tant qu\'éditeur, votre compte va devoir être examiné par notre équipe qui vous enverra un mail lorsque celui-ci sera validé ! Vous pourrez ensuite vous connecter.'
                );
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
