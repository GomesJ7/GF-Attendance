<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Matiere;
use App\Repository\MatiereRepository;

use App\Repository\SalleClasseRepository;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use App\Entity\Session;
use App\Repository\SessionRepository;
use App\Entity\Emarger;
use App\Repository\EmargerRepository;
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


/*    
#[Route('/matiere', name: 'api_matiere_new', methods: ['POST'])]
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
// emarger ---------------------------------------------
#[Route('/{id}/emarger/create', name: 'app_session_emarger_create', methods: ['GET', 'POST'])]
public function newEmargerSession(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse

{
    $session = $serializer->deserialize($request->getContent(), Session::class, 'json');
    $entityManager->persist($session);
    $entityManager->flush();
    $jsonSession = $serializer->serialize($session, 'json');
    return new JsonResponse($jsonSession, Response::HTTP_CREATED, [], true);
    
}
/*
#[Route('/{id}/emarger/update', name: 'api_session_update', methods: ['PUT'])]
public function putEmarger(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $id = $data['id'] ?? null;
    if ($id === null) {
        return new JsonResponse(['error' => 'Missing ID parameter'], Response::HTTP_BAD_REQUEST);
    }
    $emarger = $entityManager->getRepository(Emarger::class)->find($id);
    if (!$emarger) {
        return new JsonResponse(['error' => 'Update not found'], Response::HTTP_NOT_FOUND);
    }
    $entityManager->update($emarger);
    $entityManager->flush();
    return new JsonResponse(['message' => 'Signing up successfully'], Response::HTTP_OK);

}#[Route('/matiere/{id}', name:"api_matiere_update", methods:['PUT'])]
    public function updateMatiere(Request $request, SerializerInterface $serializer, Matiere $currentMatiere, EntityManagerInterface $em): JsonResponse
    {
        $updatedMatiere = $serializer->deserialize($request->getContent(),
                Matiere::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentMatiere]);
        $em->persist($updatedMatiere);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
*/
#[Route('/{id}/emarger/update', name: 'api_session_update', methods: ['PUT'])]
public function updateEmarger(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
    }
    
    $id = $data['id'] ?? null;
    if ($id === null) {
        return new JsonResponse(['error' => 'Missing ID parameter'], Response::HTTP_BAD_REQUEST);
    }
    
    $emarger = $entityManager->getRepository(Emarger::class)->find($id);
    if (!$emarger) {
        return new JsonResponse(['error' => 'Update not found'], Response::HTTP_NOT_FOUND);
    }

    // Assume you have a function to update the entity from the request data
    // $this->updateEmargerFromRequest($emarger, $data);
    // Example:
    // if (isset($data['name'])) {
    //     $emarger->setName($data['name']);
    // }
    // if (isset($data['status'])) {
    //     $emarger->setStatus($data['status']);
    // }
    
    try {
        $entityManager->persist($emarger); // Using persist in case it's a detached entity
        $entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(['error' => 'Unable to update entity', 'details' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    return new JsonResponse(['message' => 'Signing up successfully', 'data' => $emarger], Response::HTTP_OK);
    }

// authentification ----------------------------------------
#[Route('/profil', name: 'api_profil', methods: ['GET'])]
public function getProfil(SerializerInterface $serializer): JsonResponse
{
    $user = $this->getUser();
    $json = $serializer->serialize($user, 'json', ['groups' => 'getUser']);
    return new JsonResponse($json, Response::HTTP_OK, [], true);
}
// salle de classe
#[Route('/salleClasse', name: 'api_salleClasse_list', methods: ['GET'])]
public function getsalleClasseList(SalleClasseRepository $salleClasseRepository, SerializerInterface $serializer): JsonResponse
{
    $salleClasseList = $salleClasseRepository->findAll();
    $jsonSessionList = $serializer->serialize($salleClasseList, 'json', ['groups' => 'getSalleClasse']);
    return new JsonResponse($jsonSessionList, Response::HTTP_OK, [], true);
}

#[Route('/salleClasse/{id}', name:'api_sallleClasse_get', methods: ['GET'])]
public function getsalleClasse(Int $id, SalleClasseRepository $salleClasseRepository, SerializerInterface $serializer): JsonResponse
{
    $salleClasse = $salleClasseRepository->find($id);
    if ($salleClasse) {
        $jsonSalleClasse = $serializer->serialize($salleClasse, 'json', ['groups' => 'getSalleClasse']);
        return new JsonResponse($jsonSalleClasse, Response::HTTP_OK, [], true);
    }
    return new JsonResponse(null, Response::HTTP_NOT_FOUND);
}
// promotion
#[Route('/promotion', name: 'api_promotion_list', methods: ['GET'])]
public function getpromotionList(PromotionRepository $promotionRepository, SerializerInterface $serializer): JsonResponse
{
    $promotionList = $promotionRepository->findAll();
    $jsonPromotionList = $serializer->serialize($promotionList, 'json', ['groups' => 'getPromotion']);
    return new JsonResponse($jsonPromotionList, Response::HTTP_OK, [], true);
}

#[Route('/promotion/{id}', name:'api_promotion_get', methods: ['GET'])]
public function getPromotion(Int $id, PromotionRepository $promotionRepository, SerializerInterface $serializer): JsonResponse
{
    $promotion = $promotionRepository->find($id);
    if ($promotion) {
        $jsonPromotion = $serializer->serialize($promotion, 'json', ['groups' => 'getPromotion']);
        return new JsonResponse($jsonPromotion, Response::HTTP_OK, [], true);
    }
    return new JsonResponse(null, Response::HTTP_NOT_FOUND);
}
//formation
#[Route('/formation', name: 'api_formation_list', methods: ['GET'])]
public function getformationList(FormationRepository $formationRepository, SerializerInterface $serializer): JsonResponse
{
    $formationList = $formationRepository->findAll();
    $jsonFormationList = $serializer->serialize($formationList, 'json', ['groups' => 'getFormation']);
    return new JsonResponse($jsonFormationList, Response::HTTP_OK, [], true);
}

#[Route('/formation/{id}', name:'api_formation_get', methods: ['GET'])]
public function getFormation(Int $id, FormationRepository $formationRepository, SerializerInterface $serializer): JsonResponse
{
    $formation = $formationRepository->find($id);
    if ($formation) {
        $jsonFormation = $serializer->serialize($formation, 'json', ['groups' => 'getFormation']);
        return new JsonResponse($jsonFormation, Response::HTTP_OK, [], true);
    }
    return new JsonResponse(null, Response::HTTP_NOT_FOUND);
}
}