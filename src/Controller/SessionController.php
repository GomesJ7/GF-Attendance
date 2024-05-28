<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/session')]
class SessionController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/', name: 'app_session_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(SessionRepository $sessionRepository): Response
    {
        return $this->render('session/index.html.twig', [
            'sessions' => $sessionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_session_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('session/new.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_session_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_session_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('session/edit.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_session_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$session->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($session);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/emarger/create', name: 'app_session_emarger_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function emargerSession(Request $request, Session $session, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {
        $sessionEmarger = $session->getEmargers()->getValues();
        $sessionPromotion = $session->getPromotion()->getValues();

        $formBuilder = $this->createFormBuilder();
        
        if(!empty($sessionPromotion)){
            foreach ($sessionPromotion as $promotion) {
                dump($promotion->getId());
                $stagiaires = $utilisateurRepository->findByPromotion($promotion->getId());
                dump($stagiaires);
                if (!empty($stagiaires)){
                    $choices = [];
                    foreach ($stagiaires as $stagiaire) {
                        $choices[$stagiaire->getNom()]= $stagiaire->getId();
                    }
                    $formBuilder
                        ->add(
                            "users_".$promotion->getId(), 
                            ChoiceType::class,[
                                'choices' => $choices,
                                'expanded' => true,
                                'multiple' => true,
                                'choice_attr' => function($choice, string $key, mixed $value)
                                {
                                    return ['checked' => ''];
                                },
                                'label' => $promotion->getAnnee().''.$promotion->getFormation()->getSpecialite(),
                            ]);  
                    }
                    
            }}
        $form = $formBuilder->getForm();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }

        
        return $this->render('session/emarger.html.twig', [
            'form' => $form
        ]);
    }

}
