<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;

/**
 * Eremumota
 *
  */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Entity]
#[ORM\Table(name: 'eremumota')]
class Eremumota implements \Stringable
{
    /**
     * @var integer
     */
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'motaeu', type: 'text', length: 65535, nullable: true)]
    private $motaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'motaes', type: 'text', length: 65535, nullable: true)]
    private $motaes;



    /**
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     * ***** ERLAZIOAK
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     */
    /**
     * @var Udala
     */
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;
    
    public function __construct()
    {
    }

    public function __toString(): string
    {
        return $this->getMotaeu();
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
     * Set motaeu
     *
     * @param string $motaeu
     *
     * @return Eremumota
     */
    public function setMotaeu($motaeu)
    {
        $this->motaeu = $motaeu;

        return $this;
    }

    /**
     * Get motaeu
     *
     * @return string
     */
    public function getMotaeu()
    {
        return $this->motaeu;
    }

    /**
     * Set motaes
     *
     * @param string $motaes
     *
     * @return Eremumota
     */
    public function setMotaes($motaes)
    {
        $this->motaes = $motaes;

        return $this;
    }

    /**
     * Get motaes
     *
     * @return string
     */
    public function getMotaes()
    {
        return $this->motaes;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Eremumota
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
