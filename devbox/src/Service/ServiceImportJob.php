<?php

declare(strict_types=1);

namespace App\Service;

use SplFileObject;
use App\Repository\ImportJobRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Parser\ParserInterface;
use Exception;

/**
 * ServiceImportJob
 */
class ServiceImportJob
{

    /** @var EntityManager */
    private $entityManager;

    /** @var ImportJobRepository */
    private $importJobRepository;

    /**
     * __construct
     *
     * @param  ManagerRegistry $doctrine
     * @param  ImportJobRepository $importJobRepository
     * @return void
     */
    public function __construct(ManagerRegistry $doctrine, ImportJobRepository $importJobRepository)
    {
        $this->entityManager = $doctrine->getManager();
        $this->importJobRepository = $importJobRepository;
    }

    /**
     * getStartingRow
     *
     * @param  SplFileObject $file
     * @param  string $modName
     * @return int
     */
    public function getStartingRow(SplFileObject $file, string $modName, int $batchSize): int
    {
        $importJob = $this->importJobRepository->findOneByFilePathAndName($file->getPathname(), $modName);

        if (!$importJob) {
            $this->importJobRepository->addImportJob($modName, $file->getPathname(), $file->key(), $file->key() + $batchSize);
            return 0;
        }

        if ($importJob->getStatus() == "pending") {
            return $importJob->getStartingRow();
        } else {
            return $importJob->getEndingRow() + 1;
        }
    }

    /**
     * flushEntitiesAndSetStatusToComplete
     *
     * @param  SplFileObject $file
     * @param  string $modName
     * @return void
     */
    public function flushEntitiesAndSetStatusToComplete(SplFileObject $file, string $modName)
    {
        $this->entityManager->flush();
        $this->entityManager->clear();

        $importJob = $this->importJobRepository->findOneByFilePathAndName($file->getPathname(), $modName);
        if ($importJob && $importJob->getStatus() == "pending") {
            return $this->importJobRepository->updateImportJob($importJob, $importJob->getStartingRow(), $file->key(), "completed");
        }
    }

    /**
     * importBatch
     *
     * @param  SplFileObject $file
     * @param  bool $isBatchStarted
     * @param  ParserInterface $parser
     * @param  int $batchSize
     * @param  string $modName
     * @return void
     */
    public function importBatch(SplFileObject $file, bool &$isBatchStarted, ParserInterface $parser, int $batchSize, string $modName)
    {
        if ($isBatchStarted) {

            $importJob = $this->importJobRepository->findOneByFilePathAndName($file->getPathname(), $modName);
            $this->importJobRepository->updateImportJob($importJob, $importJob->getEndingRow() + 1, $file->key() + $batchSize, "pending");
            $isBatchStarted = false;
        }

        $entity = $parser->parse($file->current());
        $this->entityManager->persist($entity);


        if (($file->key() + 1) % $batchSize === 0) {
            $this->flushEntitiesAndSetStatusToComplete($file, $modName);
            $isBatchStarted = true;
        }
    }

    /**
     * executeImport
     *
     * @param  int $batchSize
     * @param  string $filepath
     * @param  ParserInterface $parser
     * @param  string $modName
     * @return array
     */
    public function executeImport(int $batchSize, string $filepath, ParserInterface $parser, string $modName): array
    {

        $file = new SplFileObject($filepath, "r");

        if (!$file) {
            return false;
        }

        $file->seek($this->getStartingRow($file, $modName, $batchSize));

        try {
            $isBatchStarted = true;
            while (!$file->eof()) {

                if ($file->current()) {
                    $this->importBatch($file, $isBatchStarted, $parser, $batchSize, $modName);
                }

                $file->next();
            }
            $this->flushEntitiesAndSetStatusToComplete($file, $modName);
            $file = null;

            return array(
                "status" => "success",
                "msg" => "$modName has been imported successfully."
            );
        } catch (Exception $e) {

            return array(
                "status" => "error",
                "msg" => $e->getMessage()
            );
        }
    }
}
