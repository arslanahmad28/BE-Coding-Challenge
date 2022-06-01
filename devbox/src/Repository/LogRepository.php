<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Log>
 *
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * add
     *
     * @param  Log $entity
     * @param  bool $flush
     * @return void
     */
    public function add(Log $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * remove
     *
     * @param  Log $entity
     * @param  bool $flush
     * @return void
     */
    public function remove(Log $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * getFiltersQueryMeta
     *
     * @return array
     */
    public function getFiltersQueryMeta(): array
    {
        return array(
            "serviceNames" => array(
                "field" => "serviceName",
                "operator" => "in"
            ),
            "startDate" => array(
                "field" => "date",
                "operator" => ">="
            ),
            "endDate" => array(
                "field" => "date",
                "operator" => "<="
            ),
            "statusCode" => array(
                "field" => "statusCode",
                "operator" => "="
            )
        );
    }

    /**
     * addFiltertoQuery
     *
     * @param  QueryBuilder $q
     * @param  string $tableAlias
     * @param  array $filterParams
     * @param  array $filtersMeta
     * @return void
     */
    protected function addFiltertoQuery($q, string $tableAlias, array $filterParams, array $filtersMeta): void
    {
        foreach ($filterParams as $param => $value) {

            $field = $filtersMeta[$param]["field"];
            $operator = $filtersMeta[$param]["operator"];

            $q->andWhere("$tableAlias.$field $operator (:$param)")
                ->setParameter($param, $value);
        }
    }

    /**
     * 
     * @return Log[] Returns an array of Log objects
     */
    public function countByFilteredFields(array $filterParams): ?int
    {
        $q = $this->createQueryBuilder('l')
            ->select('count(l.id)');

        $this->addFiltertoQuery($q, 'l', $filterParams, $this->getFiltersQueryMeta());
        return $q->getQuery()->getSingleScalarResult();
    }
}
