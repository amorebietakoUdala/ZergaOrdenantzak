<?php

namespace App\Repository;

use App\Entity\Ordenantza;
use App\Entity\Udala;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
    
    /**
     * @extends ServiceEntityRepository<Ordenantza>
     *
     * @method Ordenantza|null find($id, $lockMode = null, $lockVersion = null)
     * @method Ordenantza|null findOneBy(array $criteria, array $orderBy = null)
     * @method Ordenantza[]    findAll()
     * @method Ordenantza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class OrdenantzaRepository extends ServiceEntityRepository
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Ordenantza::class);
        }
    
        /**
         * @throws ORMException
         * @throws OptimisticLockException
         */
        public function add(Ordenantza $entity, bool $flush = true): void
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
        public function remove(Ordenantza $entity, bool $flush = true): void
        {
            $this->_em->remove($entity);
            if ($flush) {
                $this->_em->flush();
            }
        }
        
        public function findAllOrderByKodea()
        {
            return $this->findBy([], ['kodea' => 'ASC']);
        }

        public function getOrdenantzakByUdalKodea(string $kodea) {
            $qb = $this->createQueryBuilder('o')
                ->innerJoin('App:Udala','u')
                ->andWhere('u.kodea = :udalkodea')
                ->setParameter('udalkodea',$kodea)
                ->andWhere('((o.ezabatu IS NULL) or (o.ezabatu <> 1))')
                ->orderBy('o.kodea', 'ASC');
            $ordenantzak = $qb->getQuery()->getResult();
            return $ordenantzak;
        }
        
        public function findOrdenantzakByUdalKodeaOrdered($udalKodea, $order = 'ASC') {
            $qb = $this->createQueryBuilder('o');
            $qb = $this->andWhereUdalKodeaQB($qb, $udalKodea);
            $qb->orderBy('o.kodea', $order);
            return $qb->getQuery()->getResult();
        }

        private function andWhereUdalKodeaQB ( QueryBuilder $qb, $udalKodea): QueryBuilder {
            $qb->leftJoin(Udala::class,'u', Join::WITH, 'o.udala=u.id')
            ->andWhere('u.kodea = :udalaKodea')
            ->setParameter('udalaKodea', $udalKodea);
            return $qb;
        }

    }
