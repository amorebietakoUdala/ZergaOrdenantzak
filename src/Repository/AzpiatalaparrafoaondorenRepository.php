<?php

namespace App\Repository;

use App\Entity\Azpiatalaparrafoaondoren;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Azpiatalaparrafoaondoren>
 *
 * @method Azpiatalaparrafoaondoren|null find($id, $lockMode = null, $lockVersion = null)
 * @method Azpiatalaparrafoaondoren|null findOneBy(array $criteria, array $orderBy = null)
 * @method Azpiatalaparrafoaondoren[]    findAll()
 * @method Azpiatalaparrafoaondoren[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AzpiatalaparrafoaondorenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Azpiatalaparrafoaondoren::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Azpiatalaparrafoaondoren $entity, bool $flush = true): void
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
    public function remove(Azpiatalaparrafoaondoren $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
