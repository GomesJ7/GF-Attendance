<?php

namespace App\Controller;

use App\Entity\SalleClasse;
use App\Form\SalleClasseType;
use App\Repository\SalleClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/salle')]
class SalleClasseController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/', name: 'app_salle_classe_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(SalleClasseRepository $salleClasseRepository): Response
    {
        return $this->render('salle_classe/index.html.twig', [
            'salle_classes' => $salleClasseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_salle_classe_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $salleClasse = new SalleClasse();
        $form = $this->createForm(SalleClasseType::class, $salleClasse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($salleClasse);
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('salle_classe/new.html.twig', [
            'salle_classe' => $salleClasse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_classe_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(SalleClasse $salleClasse): Response
    {
        return $this->render('salle_classe/show.html.twig', [
            'salle_classe' => $salleClasse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salle_classe_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, SalleClasse $salleClasse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalleClasseType::class, $salleClasse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('salle_classe/edit.html.twig', [
            'salle_classe' => $salleClasse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salle_classe_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, SalleClasse $salleClasse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salleClasse->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($salleClasse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_salle_classe_index', [], Response::HTTP_SEE_OTHER);
    }
}
