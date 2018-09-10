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
use App\Repository\TeamRepository;

class TeamsController extends AbstractController
{
    private $serializer = null;
    private $entityManager = null;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
    * @Route("/teams", name="team_list", defaults={"_format": "json"}, methods={"GET"})
    */
    public function list(TeamRepository $repository) : Response
    {
        $teams = $repository->findAll();
        return new Response($this->serializer->serialize($teams, 'json'), Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route(
     *     "/teams",
     *      name="team_create",
     *      defaults={"_format": "json"},
     *      methods={"POST"}
     * )
     */
    public function create(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $team = new Team();
        $team
            ->setName($request->get('name'))
            ->setStrip($request->get('strip'));
        
        $entityManager->persist($team);
        $entityManager->flush();

        return new Response($this->serializer->serialize($team, 'json'), Response::HTTP_CREATED, [
            'Content-Type' => 'application/json'
        ]);
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
    public function update(
        int $id,
        Request $request,
        TeamRepository $repository,
        EntityManagerInterface $entityManager
    ) : Response {
        $team = $repository->find($id);

        if (!$team) {
            return new JsonResponse([
                'message' => 'Team not found.', 'data' => $id
            ], Response::HTTP_NOT_FOUND);
        }

        $team->setName($request->get('name'));
        $team->setStrip($request->get('strip'));

        $entityManager->flush();
        
        return new Response('', Response::HTTP_NO_CONTENT, [
            'Content-Type' => 'application/json'
        ]);
    }
}
