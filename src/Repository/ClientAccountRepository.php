<?php

namespace App\Repository;

use App\Entity\ClientAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientAccount>
 *
 * @method ClientAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientAccount[]    findAll()
 * @method ClientAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientAccount::class);
    }

//    /**
//     * @return ClientAccount[] Returns an array of ClientAccount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClientAccount
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
