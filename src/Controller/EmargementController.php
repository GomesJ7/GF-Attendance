<?php

namespace App\Controller;

use App\Entity\Emarger;
use App\Form\EmargerType;
use App\Repository\EmargerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/emargement')]
class EmargementController extends AbstractController
{
    #[Route('/', name: 'app_emargement_index', methods: ['GET'])]
    public function index(EmargerRepository $emargerRepository): Response
    {
        return $this->render('emargement/index.html.twig', [
            'emargers' => $emargerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emargement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emarger = new Emarger();
        $form = $this->createForm(EmargerType::class, $emarger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emarger);
            $entityManager->flush();

            return $this->redirectToRoute('app_emargement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emargement/new.html.twig', [
            'emarger' => $emarger,
            'form' => $form,
        ]);
    }

    #[Route('/{session}', name: 'app_emargement_show', methods: ['GET'])]
    public function show(Emarger $emarger): Response
    {
        return $this->render('emargement/show.html.twig', [
            'emarger' => $emarger,
        ]);
    }

    #[Route('/{session}/edit', name: 'app_emargement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emarger $emarger, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmargerType::class, $emarger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emargement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emargement/edit.html.twig', [
            'emarger' => $emarger,
            'form' => $form,
        ]);
    }

    #[Route('/{session}', name: 'app_emargement_delete', methods: ['POST'])]
    public function delete(Request $request, Emarger $emarger, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emarger->getSession(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($emarger);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emargement_index', [], Response::HTTP_SEE_OTHER);
    }
}
