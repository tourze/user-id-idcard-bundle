<?php

namespace Tourze\UserIDIdcardBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;

/**
 * @method IdcardIdentity|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdcardIdentity|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdcardIdentity[]    findAll()
 * @method IdcardIdentity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdcardIdentityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdcardIdentity::class);
    }
}
