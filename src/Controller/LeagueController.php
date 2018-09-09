<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class LeagueController extends AbstractController
{
    /**
     * @Route("/leagues/{id}", name="league_delete", defaults={"_format", "json"}, requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete(int $id) : Response
    {
        return new Response('', 204);
    }
}