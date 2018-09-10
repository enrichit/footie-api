<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\League;
use App\Repository\LeagueRepository;

class LeagueController extends AbstractController
{   
    /**
     * @Route("/leagues/{id}", name="league_delete", defaults={"_format", "json"}, requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete(
        int $id,
        LeagueRepository $repository,
        EntityManagerInterface $entityManager
    ) : Response {
        $league = $repository->find($id);

        if (!$league) {
            return new JsonResponse([
                'message' => 'League not found.', 'data' => $id
            ], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($league);
        $entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}