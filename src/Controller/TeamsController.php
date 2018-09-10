<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Team;

class TeamsController extends AbstractController
{
    private $serializer = null;
    private $entityManager = null;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
    * @Route("/teams", name="team_list", defaults={"_format": "json"}, methods={"GET"})
    */
    public function list() : Response
    {
        $teams = $this->entityManager
            ->getRepository(Team::class)
            ->findAll();
        
        $response = new Response($this->serializer->serialize($teams, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route(
     *     "/teams",
     *      name="team_create",
     *      defaults={"_format": "json"},
     *      methods={"POST"}
     * )
     */
    public function create(Request $request) : Response
    {
        $team = new Team();
        $team
            ->setName($request->get('name'))
            ->setStrip($request->get('strip'));
        
        $this->entityManager->persist($team);
        $this->entityManager->flush();
        
        $response = new Response($this->serializer->serialize($team, 'json'), 201);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route(
     *      "/teams/{id}",
     *      name="team_update",
     *      defaults={"_format", "json"},
     *      requirements={"id"="\d+"},
     *      methods={"PUT"}
     * )
     */
    public function update(int $id, Request $request) : Response
    {
        $team = $this->entityManager
            ->getRepository(Team::class)
            ->find($id);

        if (!$team) {
            return new JsonResponse([
                'message' => 'Team not found.', 'data' => $id
            ], 404);
        }

        $team->setName($request->get('name'));
        $team->setStrip($request->get('strip'));

        $this->entityManager->flush();
        
        $response = new Response('', 204);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
