<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticatorController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error instanceof AuthenticationException) {
            $this->addFlash('error', $error->getMessage());
        }

        $user = $this->getUser();
        
        if ($user && $this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin'); 
        } elseif ($user && $this->isGranted('ROLE_FORMATEUR')) {
            return $this->redirectToRoute('home'); 
        } elseif ($user && $this->isGranted('ROLE_STAGIAIRE')) {
            return $this->redirectToRoute('home'); 
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
