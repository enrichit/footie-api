<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Team;

class TeamsController extends AbstractController
{
    /**
    * @Route("/teams", name="team_list")
    */
    public function list()
    {
        $teams = $this->getDoctrine()
            ->getRepository(Team::class)
            ->findAll();

        return new JsonResponse($teams);
    }
}
