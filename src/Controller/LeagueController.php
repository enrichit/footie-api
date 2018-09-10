<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\League;

class LeagueController extends AbstractController
{   
    private $entityManager = null;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/leagues/{id}", name="league_delete", defaults={"_format", "json"}, requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete(int $id) : Response
    {
        $league = $this->entityManager
            ->getRepository(League::class)
            ->find($id);

        if (!$league) {
            return new JsonResponse([
                'message' => 'League not found.', 'data' => $id
            ], 404);
        }

        $this->entityManager->remove($league);
        $this->entityManager->flush();
        return new Response('', 204);
    }
}