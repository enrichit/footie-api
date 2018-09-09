<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Team;
use App\Entity\League;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $league = new League();
        $league->setName('Premier League');

        $team = new Team();
        $team->setName('Chelsea');
        $team->setStrip('Blue');
        
        $manager->persist($league);
        $manager->persist($team);
        $manager->flush();
    }
}
