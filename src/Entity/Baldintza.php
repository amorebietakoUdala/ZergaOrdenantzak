<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\UdalaEgiaztatu;
use App\Repository\BaldintzaRepository;

/**
 * Baldintza
 *
 * @ORM\Table(name="baldintza")
 * @ORM\Entity(repositoryClass=BaldintzaRepository::class)
 * @UdalaEgiaztatu(userFieldName="udala_id")
 */
class Baldintza
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="baldintzaeu", type="text", length=65535, nullable=true)
     */
    private $baldintzaeu;

    /**
     * @var string
     *
     * @ORM\Column(name="baldintzaes", type="text", length=65535, nullable=true)
     */
    private $baldintzaes;

    /**
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     * ***** ERLAZIOAK
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     */
    
    /**
     * @var Udala $udala
     * @ORM\ManyToOne(targetEntity="Udala")
     */
    private $udala;
    
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getBaldintzaeu();
    }
    /**
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     * ***** ERLAZIOAK
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     */

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set baldintzaeu
     *
     * @param string $baldintzaeu
     *
     * @return Baldintza
     */
    public function setBaldintzaeu($baldintzaeu)
    {
        $this->baldintzaeu = $baldintzaeu;

        return $this;
    }

    /**
     * Get baldintzaeu
     *
     * @return string
     */
    public function getBaldintzaeu()
    {
        return $this->baldintzaeu;
    }

    /**
     * Set baldintzaes
     *
     * @param string $baldintzaes
     *
     * @return Baldintza
     */
    public function setBaldintzaes($baldintzaes)
    {
        $this->baldintzaes = $baldintzaes;

        return $this;
    }

    /**
     * Get baldintzaes
     *
     * @return string
     */
    public function getBaldintzaes()
    {
        return $this->baldintzaes;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Baldintza
     */
    public function setUdala(Udala $udala = null)
    {
        $this->udala = $udala;

        return $this;
    }

    /**
     * Get udala
     *
     * @return Udala
     */
    public function getUdala()
    {
        return $this->udala;
    }
}
