<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LogRepository;

/**
 * LogsController
 */
class LogsController extends AbstractController
{
    /**
     * @var LogRepository
     */
    private $logRepository;

    /**
     * __construct
     *
     * @param  LogRepository $logRepository
     * @return void
     */
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    #[Route('/count', methods: ['GET'])]
    public function filteredRecordsCounter(Request $request): JsonResponse
    {
        $filteredRowsCount = $this->logRepository->countByFilteredFields($request->query->all());
        return $this->json(["counter" => $filteredRowsCount]);
    }
}
