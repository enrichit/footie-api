<?php

namespace App\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Team;

class ApiAvailabilityTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $team = new Team();
        $team
            ->setName('hello')
            ->setStrip('world');

        $this->entityManager->persist($team);
        $this->entityManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
    
    /**
     * @dataProvider urlProvider
     */
    public function testAllAvailableRoutes($method, $url, $params = [])
    {
        $client = self::createClient();
        $client->request($method, $url, $params);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['GET', '/teams'];
        yield ['POST', '/teams', ['name' => 'hello', 'strip' => 'world']];
        yield ['PUT', '/teams/1', ['name' => 'hello', 'strip' => 'world']];
    }
}