<?php

namespace App\Repository;

use App\Entity\Historikoa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ordenantza>
 *
 * @method Historikoa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historikoa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historikoa[]    findAll()
 * @method Historikoa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistorikoaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historikoa::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Historikoa $entity, bool $flush = true): void
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
    public function remove(Historikoa $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
