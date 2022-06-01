<?php

declare(strict_types=1);

namespace Tests\Integration\DataFixtures;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataFixtureTestCase extends WebTestCase
{
    /** @var  Application $application */
    protected static $application;

    /** @var  Client $client */
    protected static $client;

    /** @var  ContainerInterface $container */
    protected $dtcontainer;

    /** @var  EntityManager $entityManager */
    protected $entityManager;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
        }

        $this->dtcontainer = self::$client->getContainer();
        $this->entityManager = $this->dtcontainer->get('doctrine.orm.entity_manager');
        self::executeDBConfigCommands();
        parent::setUp();
    }

    protected static function executeDBConfigCommands()
    {
        self::runCommand('doctrine:database:drop --env=test --force');
        self::runCommand('doctrine:database:create --env=test');
        self::runCommand('doctrine:schema:create --env=test');
        self::runCommand('doctrine:fixtures:load --env=test --no-interaction');
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            if (null === self::$client) {
                self::$client = static::createClient();
            }

            self::$application = new Application(self::$client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        self::runCommand('doctrine:database:drop --env=test --force');
    }
}
