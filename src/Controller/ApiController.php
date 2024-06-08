<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Formation;
use App\Entity\Matiere;
use App\Entity\Session;
use App\Repository\SessionRepository;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

// matiere ---------------------------------------------

    #[Route('/matiere', name: 'api_matiere_list', methods: ['GET'])]
    public function getMatiereList(MatiereRepository $matiereRepository, SerializerInterface $serializer): JsonResponse
    {
        $matiereList = $matiereRepository->findAll();
        $jsonMatiereList = $serializer->serialize($matiereList, 'json', ['groups' => 'getMatiere']);
        return new JsonResponse($jsonMatiereList, Response::HTTP_OK, [], true);
    }

    #[Route('/matiere/{id}', name:'api_matiere_get', methods: ['GET'])]
    public function getMatiere(Int $id, MatiereRepository $matiereRepository, SerializerInterface $serializer): JsonResponse
    {
        $matiere = $matiereRepository->find($id);
        if($matiere){
            $jsonMatiere = $serializer->serialize($matiere, 'json', ['groups' => 'getMatiere']);
            return new JsonResponse($jsonMatiere, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

/*    #[Route('/matiere', name: 'api_matiere_new', methods: ['POST'])]
    public function newMatiere(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $matiere = $serializer->deserialize($request->getContent(), Matiere::class, 'json');
        $em->persist($matiere);
        $em->flush();
        $jsonMatiere = $serializer->serialize($matiere, 'json');
        return new JsonResponse($jsonMatiere, Response::HTTP_CREATED, [], true);
    }


#[Route('/matiere', name: 'api_matiere_delete', methods: ['DELETE'])]
public function deleteMatiere(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $id = $data['id'] ?? null;
    if ($id === null) {
        return new JsonResponse(['error' => 'Missing ID parameter'], Response::HTTP_BAD_REQUEST);
    }
    $matiere = $entityManager->getRepository(Matiere::class)->find($id);
    if (!$matiere) {
        return new JsonResponse(['error' => 'Matiere not found'], Response::HTTP_NOT_FOUND);
    }
    $entityManager->remove($matiere);
    $entityManager->flush();
    return new JsonResponse(['message' => 'Matiere deleted successfully'], Response::HTTP_OK);
}*/

// sesion ---------------------------------------------

#[Route('/session', name: 'api_session_list', methods: ['GET'])]
public function getSessionList(SessionRepository $sessionRepository, SerializerInterface $serializer): JsonResponse
{
    $sessionList = $sessionRepository->findAll();
    $jsonSessionList = $serializer->serialize($sessionList, 'json', ['groups' => 'getSession']);
    return new JsonResponse($jsonSessionList, Response::HTTP_OK, [], true);
}

#[Route('/session/{id}', name:'api_session_get', methods: ['GET'])]
public function getSession(Int $id, SessionRepository $sessionRepository, SerializerInterface $serializer): JsonResponse
{
    $session = $sessionRepository->find($id);
    if ($session) {
        $jsonSession = $serializer->serialize($session, 'json', ['groups' => 'getSession']);
        return new JsonResponse($jsonSession, Response::HTTP_OK, [], true);
    }
    return new JsonResponse(null, Response::HTTP_NOT_FOUND);
}

#[Route('/{id}/emarger/create', name: 'app_session_emarger_create', methods: ['GET', 'POST'])]
public function newEmargerSession(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
{
    $session = $serializer->deserialize($request->getContent(), Session::class, 'json');
    $entityManager->persist($session);
    $entityManager->flush();
    $jsonSession = $serializer->serialize($session, 'json');
    return new JsonResponse($jsonSession, Response::HTTP_CREATED, [], true);
    
}


// authentification ----------------------------------------
#[Route('/profil', name: 'api_profil', methods: ['GET'])]
    public function getProfil(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $json = $serializer->serialize($user, 'json', ['groups' => 'getUser']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


}
