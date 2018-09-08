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
        $kernel->boot();
        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:create");
        $this->runConsole("doctrine:fixtures:load", array("--fixtures" => __DIR__ . "/../DataFixtures"));    }

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