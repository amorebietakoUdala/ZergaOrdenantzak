<?php

    namespace App\Repository;

    use App\Entity\Ordenantzaparrafoa;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use Doctrine\Persistence\ManagerRegistry;
    use Gedmo\Sortable\Entity\Repository\SortableRepository;


     /**
     * @extends ServiceEntityRepository<Ordenantzaparrafoa>
     *
     * @method Ordenantzaparrafoa|null find($id, $lockMode = null, $lockVersion = null)
     * @method Ordenantzaparrafoa|null findOneBy(array $criteria, array $orderBy = null)
     * @method Ordenantzaparrafoa[]    findAll()
     * @method Ordenantzaparrafoa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class OrdenantzaparrafoaRepository extends ServiceEntityRepository
    {

        private $sortableRepository;

        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Ordenantzaparrafoa::class);
            $this->sortableRepository = new SortableRepository(
                $this->_em,
                $this->getClassMetadata()
            );
        }
    
        /**
         * @throws ORMException
         * @throws OptimisticLockException
         */
        public function add(Ordenantzaparrafoa $entity, bool $flush = true): void
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
        public function remove(Ordenantzaparrafoa $entity, bool $flush = true): void
        {
            $this->_em->remove($entity);
            if ($flush) {
                $this->_em->flush();
            }
        }

        // if there is the method is not defined in this class and exists in sortable repository class, redirects the call to it, else throws exception
        public function __call($method, $arguments): mixed
        {
            if (method_exists($this,$method)) {
                return call_user_func_array([$this, $method], $arguments);
            } else if (method_exists($this->sortableRepository, $method)) {
                return call_user_func_array([$this->sortableRepository, $method], $arguments);
            }
        
            throw new \BadMethodCallException("El método $method no existe en " . self::class);
        }

    }
