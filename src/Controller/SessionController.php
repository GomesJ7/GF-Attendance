<?php

namespace App\Controller;

use App\Entity\Emarger;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Entity\Utilisateur;
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
        // Récupération des émargements et promotions liés à la session
        $sessionEmarger = $session->getEmargers()->getValues();
        $sessionPromotions = $session->getPromotion()->getValues(); // Assurez-vous que getPromotions() retourne bien un tableau de promotions

        // Initialisation du form builder
        $formBuilder = $this->createFormBuilder();

        // Si le tableau des promotions n'est pas vide
        if (!empty($sessionPromotions)) {
            foreach ($sessionPromotions as $promotion) {
                // Récupération des utilisateurs de la promotion
                $stagiaires = $utilisateurRepository->findByPromotion($promotion->getId());

                // Si le tableau des utilisateurs n'est pas vide
                if (!empty($stagiaires)) {
                    $choices = [];
                    foreach ($stagiaires as $stagiaire) {
                        // Ajout des utilisateurs dans un tableau de choix
                        $choices[$stagiaire->getNom()] = $stagiaire->getId();
                    }

                    // Ajout d'un champ de type ChoiceType au form builder
                    $formBuilder->add(
                        "users_" . $promotion->getId(),
                        ChoiceType::class, [
                            'choices' => $choices,
                            'expanded' => true,
                            'multiple' => true,
                            'choice_attr' => function($choice, string $key, mixed $value) {
                                // Par défaut, tous les choix sont cochés
                                return ['checked' => ''];
                            },
                            'label' => $promotion->getAnnee() . ' ' . $promotion->getFormation()->getSpecialite(),
                        ]
                    );
                }
            }
        }

        // Ajout des champs supplémentaires
        $formBuilder->add('alternative', TextType::class)
                    ->add('heureArrivee', DateTimeType::class, [
                        'widget' => 'single_text',
                        'placeholder' => [
                            'year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                        ],
                    ])
                    ->add('heureDepart', DateTimeType::class, [
                        'widget' => 'single_text',
                        'placeholder' => [
                            'year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                        ],
                    ]);

        // Génération du formulaire
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Vider les émargements existants pour la session
            if (!empty($sessionEmarger)) {
                foreach ($sessionEmarger as $emarger) {
                    $entityManager->remove($emarger);
                }
                $entityManager->flush();
            }

            // Récupération des données du formulaire
            $data = $form->getData();

            // Extraction des valeurs des champs supplémentaires
            $alternative = $data['alternative'];
            $heureArrivee = $data['heureArrivee'] instanceof \DateTimeInterface ? \DateTimeImmutable::createFromMutable($data['heureArrivee']) : null;
            $heureDepart = $data['heureDepart'] instanceof \DateTimeInterface ? \DateTimeImmutable::createFromMutable($data['heureDepart']) : null;

            foreach ($data as $promotionKey => $utilisateurIds) {
                if (is_array($utilisateurIds)) {
                    foreach ($utilisateurIds as $utilisateurId) {
                        $emarger = new Emarger();
                        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($utilisateurId);

                        if ($utilisateur) {
                            $emarger->setPresence(true); // Présence cochée
                            $emarger->setAlternative($alternative);
                            $emarger->setHeureArrivee($heureArrivee);
                            $emarger->setHeureDepart($heureDepart);
                            $emarger->setSession($session);
                            $emarger->setUtilisateur($utilisateur);
                            $entityManager->persist($emarger);
                        }
                    }
                }

                // Gérer les utilisateurs non cochés (absents)
                $allStagiaires = $utilisateurRepository->findByPromotion($promotionKey);
                $checkedUtilisateurIds = is_array($utilisateurIds) ? $utilisateurIds : [];

                foreach ($allStagiaires as $stagiaire) {
                    if (!in_array($stagiaire->getId(), $checkedUtilisateurIds)) {
                        $emarger = new Emarger();
                        $emarger->setPresence(false); // Présence non cochée
                        $emarger->setSession($session);
                        $emarger->setUtilisateur($stagiaire);
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
