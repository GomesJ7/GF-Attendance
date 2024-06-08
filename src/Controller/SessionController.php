<?php

namespace App\Controller;

use App\Entity\Emarger;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Entity\Utilisateur;
use App\Entity\Promotion;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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

    #[Route('/utilisateur', name: 'app_session_utilisateur', methods: ['GET'])]
    /*public function sessions(): Response
    {
        $utilisateur = $this->getUser();
        
        $promotions = $utilisateur->getPromotion()->getValues();
        $sessions = [];

        foreach ($promotions as $promotion) {
            foreach ($promotion->getSessions() as $session) {
                if (!in_array($session, $sessions)) {
                    $sessions[] = $session;
                }
            }
        }

        return $this->render('session/sessionU.html.twig', [
            'sessions' => $sessions,
        ]);
    }*/

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
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_FORMATEUR")'))]
    public function emargerSession(Request $request, Session $session, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupération des promotions liées à la session
        $sessionPromotions = $session->getPromotion()->getValues();
    
        // Initialisation du form builder
        $formBuilder = $this->createFormBuilder();
        
        $date = $session->getDateSession();
        $heureDebut = $session->getHeureDebut();
        $heureFin = $session->getHeureFin();
    
        // Si le tableau des promotions n'est pas vide
        if (!empty($sessionPromotions)) {
            foreach ($sessionPromotions as $promotion) {
                // Récupération des utilisateurs de la promotion
                $stagiaires = $utilisateurRepository->findByPromotion($promotion->getId());
    
                // Si le tableau des utilisateurs n'est pas vide
                if (!empty($stagiaires)) {
                    foreach ($stagiaires as $stagiaire) {
                        // Création des objets DateTimeImmutable combinés
                        $datetimeDebut = \DateTimeImmutable::createFromFormat(
                            'Y-m-d H:i:s', 
                            $date->format('Y-m-d') . ' ' . $heureDebut->format('H:i:s')
                        );
                        $datetimeFin = \DateTimeImmutable::createFromFormat(
                            'Y-m-d H:i:s', 
                            $date->format('Y-m-d') . ' ' . $heureFin->format('H:i:s')
                        );
    
                        // Ajout d'un sous-formulaire pour chaque stagiaire
                        $formBuilder->add('stagiaire_' . $stagiaire->getId(), FormType::class, [
                            'label' => $stagiaire->getNom(),
                            'mapped' => false,
                            'data_class' => null,
                            'by_reference' => false,
                        ])
                        ->add('presence_' . $stagiaire->getId(), CheckboxType::class, [
                            'label' => 'Présent',
                            'required' => false,
                        ])
                        ->add('heureArrivee_' . $stagiaire->getId(), DateTimeType::class, [
                            'widget' => 'single_text',
                            'required' => false,
                            'data' => $datetimeDebut, // Préremplir avec l'heure de début de la session
                        ])
                        ->add('heureDepart_' . $stagiaire->getId(), DateTimeType::class, [
                            'widget' => 'single_text',
                            'required' => false,
                            'data' => $datetimeFin, // Préremplir avec l'heure de fin de la session
                        ])
                        ->add('alternative_' . $stagiaire->getId(), TextType::class, [
                            'required' => false,
                            'data' => 'vide', // Préremplir le champ avec "vide"
                        ]);
                    }
                }
            }
        }
    
        // Génération du formulaire
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
    
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Vider les émargements existants pour la session
            $sessionEmarger = $session->getEmargers()->getValues();
            if (!empty($sessionEmarger)) {
                foreach ($sessionEmarger as $emarger) {
                    $entityManager->remove($emarger);
                }
                $entityManager->flush();
            }
    
            // Récupération des données du formulaire
            $data = $form->getData();
    
            foreach ($sessionPromotions as $promotion) {
                $stagiaires = $utilisateurRepository->findByPromotion($promotion->getId());
    
                foreach ($stagiaires as $stagiaire) {
                    $emarger = new Emarger();
                    $utilisateur = $stagiaire;
    
                    if ($utilisateur) {
                        $presence = $form->get('presence_' . $stagiaire->getId())->getData();
                        $heureArrivee = $form->get('heureArrivee_' . $stagiaire->getId())->getData();
                        $heureDepart = $form->get('heureDepart_' . $stagiaire->getId())->getData();
                        $alternative = $form->get('alternative_' . $stagiaire->getId())->getData();
    
                        $emarger->setPresence($presence); // Présence cochée
                        $emarger->setAlternative($alternative);
                        $emarger->setHeureArrivee($heureArrivee instanceof \DateTimeInterface ? \DateTimeImmutable::createFromMutable($heureArrivee) : null);
                        $emarger->setHeureDepart($heureDepart instanceof \DateTimeInterface ? \DateTimeImmutable::createFromMutable($heureDepart) : null);
                        $emarger->setSession($session);
                        $emarger->setUtilisateur($utilisateur);
                        $entityManager->persist($emarger);
                    }
                }
            }
    
            // Sauvegarde des nouveaux émargements
            $entityManager->flush();
    
            // Redirection vers la liste des sessions
            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }
    
        // Affichage du formulaire dans le template Twig
        return $this->render('session/emarger.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}