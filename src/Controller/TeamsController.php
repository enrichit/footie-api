<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Team;
use Symfony\Component\HttpFoundation\Request;

class TeamsController extends AbstractController
{
    /**
    * @Route("/teams", name="team_list", defaults={"_format": "json"}, methods={"GET"})
    */
    public function list() : JsonResponse
    {
        $teams = $this->getDoctrine()
            ->getRepository(Team::class)
            ->findAll();

        return new JsonResponse($teams);
    }

    /**
     * @Route(
     *     "/teams",
     *      name="team_create",
     *      defaults={"_format": "json"},
     *      methods={"POST"}
     * )
     */
    public function create(string $name, string $strip) : JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();

        $team = new Team();
        $team->setName($name)->setStrip($strip);
        
        $manager->persist($team);
        $manager->flush();

        return new JsonResponse($team);
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
    public function update(int $id) : JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();

        $team = $this->getDoctrine()
            ->getRepository(Team::class)
            ->find($id);

        if (!$team) {
            return $this->createNotFoundException("Team with id {$id} not found.");
        }
        
        $request = Request::createFromGlobals();

        $team->setName($request->get('name'));
        $team->setName($request->get('strip'));

        $manager->flush();

        return new JsonResponse($team);
    }
}
