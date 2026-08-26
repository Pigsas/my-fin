<?php

namespace App\Repository;

use App\Entity\Series;
use App\Enum\InvoiceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends ServiceEntityRepository<Series>
 */
class SeriesRepository extends EntityRepository implements RepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Series::class));
    }

    //    /**
    //     * @return Series[] Returns an array of Series objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Series
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function exists(string $value, InvoiceType $invoiceType): bool
    {
        return (bool) $this->createQueryBuilder('s')
            ->select('1')
            ->where('s.series = :code')
            ->AndWhere('s.type = :type OR s.type IS NULL')
            ->setParameter('code', $value)
            ->setParameter('type', $invoiceType)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
