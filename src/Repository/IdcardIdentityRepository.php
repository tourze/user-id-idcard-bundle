<?php

declare(strict_types=1);

namespace Tourze\UserIDIdcardBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;

/**
 * @extends ServiceEntityRepository<IdcardIdentity>
 */
#[AsRepository(entityClass: IdcardIdentity::class)]
#[Autoconfigure(public: true)]
class IdcardIdentityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdcardIdentity::class);
    }

    public function save(IdcardIdentity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IdcardIdentity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
