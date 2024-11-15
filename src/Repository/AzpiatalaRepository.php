<?php

namespace App\Repository;

use App\Entity\Azpiatala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Azpiatala>
 *
 * @method Azpiatala|null find($id, $lockMode = null, $lockVersion = null)
 * @method Azpiatala|null findOneBy(array $criteria, array $orderBy = null)
 * @method Azpiatala[]    findAll()
 * @method Azpiatala[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AzpiatalaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Azpiatala::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Azpiatala $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Azpiatala $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
