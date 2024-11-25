<?php

    namespace App\Entity;

    use AMREU\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;
    use App\Annotation\UdalaEgiaztatu;

    /**
     * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
     * @ORM\Table(name="user")
     * @UdalaEgiaztatu(userFieldName="udala_id")
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
         * @ORM\Column(type="string", length=180, unique=true)
         */
        protected $username;

        /**
         * @ORM\Column(type="json")
         */
        protected $roles = [];

        /**
         * @var string The hashed password
         * @ORM\Column(type="string")
         */
        protected $password;

        /**
         * @ORM\Column(type="string", length=255)
         */
        protected $firstName;

        /**
         * @ORM\Column(type="string", length=255)
         */
        protected $email;

        /**
         * @ORM\Column(type="boolean", options={"default":"1"}, nullable=false)
         */
        protected $activated = true;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         */
        protected $lastLogin;


        /**
         * @var string
         * @ORM\Column(name="hizkuntza", type="string", length=10, nullable=true)
         */
        private $hizkuntza;

        /**
         * @var Udala
         * @ORM\ManyToOne(targetEntity="Udala")
         */
        private $udala;

    /**
     * Set udala
     *
     * @param \App\Entity\Udala $udala
     *
     * @return User
     */
    public function setUdala(\App\Entity\Udala $udala = null)
    {
        $this->udala = $udala;

        return $this;
    }

    /**
     * Get udala
     *
     * @return \App\Entity\Udala
     */
    public function getUdala()
    {
        return $this->udala;
    }

    /**
     * Set hizkuntza
     *
     * @param string $hizkuntza
     *
     * @return User
     */
    public function setHizkuntza($hizkuntza)
    {
        $this->hizkuntza = $hizkuntza;

        return $this;
    }

    /**
     * Get hizkuntza
     *
     * @return string
     */
    public function getHizkuntza()
    {
        return $this->hizkuntza;
    }
}
