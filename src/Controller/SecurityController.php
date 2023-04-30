<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: 'mot-de-passe-oublie', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request, 
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGeneratorInterface,
        EntityManagerInterface $entityManager,
        SendMailService $mail
        ): Response
    {
        $form = $this->createForm( ResetPasswordRequestFormType::class );

        // On récupére la requête pour pouvoir la traiter
        // Les données du formulaire seront stockées dans la variable
        // $form ( voir un dd($form))
        $form->handleRequest($request);

        // Après la méthode handleRequest sur la variable $form
        // On vérifie si le formulaire a été envoyé et si il est 
        // valide (aucune erreur)
        if($form->isSubmitted() && $form->isValid()) {
            // On recherche dans le UserRepository et on récupère
            // la ligne de l'utilisateur qui correspond à l'email
            // récupérer du formulaire
            // La méthode findOneByEmail renverra uniquement un résultat 
            // sous la forme d'une Entité User si elle existe sinon null
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            //on vérifie si on a trouver un utilisateur
            if($user){
                // On génère un token de réinitialisation avec le générateur
                // de token inclus dans Symfony
                $token = $tokenGeneratorInterface->generateToken();

                // On modifie le resetToken de l'entité User puis on 
                // persist et flush pour répercuter les modification
                // dans la base de données
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // On génère un lien de réinitialisation de mot de passe
                $url = $this->generateUrl(
                    'reset_pass', 
                    ['token' => $token], 
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                //on crée les données du mail
                $context = [
                    'url' => $url,
                    'user' => $user
                ];

                $mail->send(
                    'tamagotabemasu@gmail.com',
                    $user->getEmail(),
                    'Réinitialisation de mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Email envoyé avec succès');

                return $this->redirectToRoute('app_login');
            }
            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'passwordForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/mot-de-passe-oublie/{token}', name:'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = $userRepository->findOneByResetToken($token);

        if($user) {
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if( $form->isSubmitted() && $form->isValid() ) {
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user, $form->get('password')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'newPasswordForm' => $form->createView(),
            ]);
        }
        $this->addFlash('danger', 'Token invalide');
        return $this->redirectToRoute('app_login');
    }
}
