<?php

    namespace AppBundle\Entity;

    use FOS\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name="fos_user")
     */
    class User extends BaseUser
    {

        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;


        /**
         * @var Udala
         * @ORM\ManyToOne(targetEntity="Udala")
         */
        private $udala;

        
        public function __construct()
        {
            parent::__construct();
            // your own logic
        }
    
    /**
     * Set udala
     *
     * @param \AppBundle\Entity\Udala $udala
     *
     * @return User
     */
    public function setUdala(\AppBundle\Entity\Udala $udala = null)
    {
        $this->udala = $udala;

        return $this;
    }

    /**
     * Get udala
     *
     * @return \AppBundle\Entity\Udala
     */
    public function getUdala()
    {
        return $this->udala;
    }
}
