<?php

namespace App\Repository;

use App\Dto\ClientGetDto;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends EntityRepository implements RepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Client::class));
    }



    //    /**
    //     * @return Client[] Returns an array of Client objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function withPagination(array $criteria, array $sorting)
    {
//        $this->createQueryBuilder('client')
//            ->addCriteria($criteria)
    }

    public function search(ClientGetDto $clientGetDto)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name LIKE :name OR c.code = :code')
            ->setParameter('name', '%'.$clientGetDto->name."%")
            ->setParameter('code', $clientGetDto->code)
            ->getQuery()
            ->getResult();
    }
}
