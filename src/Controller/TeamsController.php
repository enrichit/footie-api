<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\Team;

class TeamsController extends AbstractController
{
    private $serializer = null;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
    * @Route("/teams", name="team_list", defaults={"_format": "json"}, methods={"GET"})
    */
    public function list() : Response
    {
        $teams = $this->getDoctrine()
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
        $manager = $this->getDoctrine()->getManager();

        $team = new Team();
        $team
            ->setName($request->get('name'))
            ->setStrip($request->get('strip'));
        
        $manager->persist($team);
        $manager->flush();
        
        $response = new Response($this->serializer->serialize($team, 'json'));
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
        $manager = $this->getDoctrine()->getManager();

        $team = $this->getDoctrine()
            ->getRepository(Team::class)
            ->find($id);

        if (!$team) {
            return new JsonResponse([
                'message' => 'Team not found.', 'data' => $id
            ], 404);
        }

        $team->setName($request->get('name'));
        $team->setName($request->get('strip'));

        $manager->flush();
        
        $response = new Response($this->serializer->serialize($team, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
