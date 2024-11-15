<?php

    namespace App\Repository;

    use App\Entity\Ordenantza;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use Doctrine\ORM\Query;
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
        
        public function getOrdenantzabat ( $id )
        {
            $em = $this->getEntityManager();


            $dql = "
            SELECT o,p,a,ap,az,azp,k,m,b
                FROM App:Ordenantza o
                    LEFT JOIN o.parrafoak p
                    LEFT JOIN o.atalak a
                    LEFT JOIN a.parrafoak ap
                    LEFT JOIN a.azpiatalak az
                    LEFT JOIN az.parrafoak azp
                    LEFT JOIN az.kontzeptuak k
                    LEFT JOIN k.kontzeptumota m
                    LEFT JOIN k.baldintza b
                WHERE o.id = :id
        ";


            $consulta = $em->createQuery( $dql );
            $consulta->setParameter( 'id', $id );

            return $consulta->getResult( Query::HYDRATE_ARRAY );

        }

        public function findAllOrderByKodea()
        {
            return $this->findBy(array(), array('kodea' => 'ASC'));
        }

    }
