<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ImportJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImportJob>
 *
 * @method ImportJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImportJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImportJob[]    findAll()
 * @method ImportJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportJobRepository extends ServiceEntityRepository
{
    /**
     * __construct
     *
     * @param  mixed $registry
     * @return void
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportJob::class);
    }

    /**
     * addImportJob
     *
     * @param  string $modName
     * @param  string $filepath
     * @param  int $startingLine
     * @param  int $endingLine
     * @return ImportJob
     */
    public function addImportJob(string $modName, string $filepath, int $startingLine, int $endingLine): ImportJob
    {
        $importJob = new ImportJob;
        $importJob->setName($modName);
        $importJob->setFilePath($filepath);
        $importJob->setStartingRow($startingLine);
        $importJob->setEndingRow($endingLine);
        $importJob->setStatus("pending");
        $this->add($importJob, true);
        return $importJob;
    }

    /**
     * updateImportJob
     *
     * @param  ImportJob $importJob
     * @param  int $startingLine
     * @param  int $endingLine
     * @param  string $status
     * @return ImportJob
     */
    public function updateImportJob(ImportJob $importJob, int $startingLine, int $endingLine, string $status): ImportJob
    {
        $importJob->setStartingRow($startingLine);
        $importJob->setEndingRow($endingLine);
        $importJob->setStatus($status);
        $this->add($importJob, true);
        return $importJob;
    }

    /**
     * add
     *
     * @param  ImportJob $entity
     * @param  bool $flush
     * @return void
     */
    public function add(ImportJob $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * remove
     *
     * @param  ImportJob $entity
     * @param  bool $flush
     * @return void
     */
    public function remove(ImportJob $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * findOneByFilePathAndName
     *
     * @param  string $filepath
     * @param  string $name
     * @return ImportJob
     */
    public function findOneByFilePathAndName(string $filepath, string $name): ?ImportJob
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.name = :name')
            ->setParameter('name', $name)
            ->andWhere('i.filepath = :filepath')
            ->setParameter('filepath', $filepath)
            ->orderBy('i.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
