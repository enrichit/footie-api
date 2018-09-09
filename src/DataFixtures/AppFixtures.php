<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Team;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('Chelsea');
        $team->setStrip('Blue');
        
        $manager->persist($team);
        $manager->flush();
    }
}