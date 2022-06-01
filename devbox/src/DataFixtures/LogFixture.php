<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Log;
use DateTime;

class LogFixture extends Fixture
{
    /**
     * load
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $log = new Log;
        $log->setServiceName("USER-SERVICE");
        $log->setDate(new DateTime("2021-08-17 09:21:53"));
        $log->setRequest("POST /users HTTP/1.1");
        $log->setStatusCode(201);
        $manager->persist($log);

        $log = new Log;
        $log->setServiceName("INVOICE-SERVICE");
        $log->setDate(new DateTime("2021-08-18 12:22:53"));
        $log->setRequest("GET /invoices HTTP/1.1");
        $log->setStatusCode(201);
        $manager->persist($log);

        $log = new Log;
        $log->setServiceName("INVOICE-SERVICE");
        $log->setDate(new DateTime("2021-09-12 12:22:53"));
        $log->setRequest("GET /invoices HTTP/1.1");
        $log->setStatusCode(401);
        $manager->persist($log);

        $log = new Log;
        $log->setServiceName("USER-SERVICE");
        $log->setDate(new DateTime("2021-10-18 12:22:53"));
        $log->setRequest("GET /users HTTP/1.1");
        $log->setStatusCode(400);
        $manager->persist($log);

        $log = new Log;
        $log->setServiceName("USER-SERVICE");
        $log->setDate(new DateTime("2021-11-21 12:22:53"));
        $log->setRequest("GET /users HTTP/1.1");
        $log->setStatusCode(201);
        $manager->persist($log);

        $log = new Log;
        $log->setServiceName("USER-SERVICE");
        $log->setDate(new DateTime("2021-11-21 12:22:53"));
        $log->setRequest("GET /users HTTP/1.1");
        $log->setStatusCode(400);

        $manager->persist($log);

        $manager->flush();
    }
}
