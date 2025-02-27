<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;

/**
 * Eremua
 *
 */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Entity]
#[ORM\Table(name: 'eremua')]
#[ORM\Index(name: 'eremumota_id_idx', columns: ['eremumota_id'])]
#[ORM\Index(name: 'formula_id_idx', columns: ['formula_id'])]
class Eremua implements \Stringable
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
    #[ORM\Column(name: 'izena', type: 'text', length: 65535, nullable: true)]
    private $izena;

    /**
     * @var string
     */
    #[ORM\Column(name: 'etiketaeu', type: 'text', length: 65535, nullable: true)]
    private $etiketaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'etiketaes', type: 'text', length: 65535, nullable: true)]
    private $etiketaes;



    /**
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     * ***** ERLAZIOAK
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     */
    /**
     * @var Eremumota $eremumota
     */
    #[ORM\ManyToOne(targetEntity: Eremumota::class)]
    #[ORM\JoinColumn(name: 'eremumota_id', referencedColumnName: 'id')]
    private $eremumota;

    /**
     * @var Formula $formula
     */
    #[ORM\ManyToOne(targetEntity: Formula::class)]
    #[ORM\JoinColumn(name: 'formula_id', referencedColumnName: 'id')]
    private $formula;

    /**
     * @var Udala $udala
     */
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;

    public function __construct()
    {
    }

    public function __toString(): string
    {
        return $this->getIzena();
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
     * Set izena
     *
     * @param string $izena
     *
     * @return Eremua
     */
    public function setIzena($izena)
    {
        $this->izena = $izena;

        return $this;
    }

    /**
     * Get izena
     *
     * @return string
     */
    public function getIzena()
    {
        return $this->izena;
    }

    /**
     * Set etiketaeu
     *
     * @param string $etiketaeu
     *
     * @return Eremua
     */
    public function setEtiketaeu($etiketaeu)
    {
        $this->etiketaeu = $etiketaeu;

        return $this;
    }

    /**
     * Get etiketaeu
     *
     * @return string
     */
    public function getEtiketaeu()
    {
        return $this->etiketaeu;
    }

    /**
     * Set etiketaes
     *
     * @param string $etiketaes
     *
     * @return Eremua
     */
    public function setEtiketaes($etiketaes)
    {
        $this->etiketaes = $etiketaes;

        return $this;
    }

    /**
     * Get etiketaes
     *
     * @return string
     */
    public function getEtiketaes()
    {
        return $this->etiketaes;
    }

    /**
     * Set eremumota
     *
     * @param Eremumota $eremumota
     *
     * @return Eremua
     */
    public function setEremumota(Eremumota $eremumota = null)
    {
        $this->eremumota = $eremumota;

        return $this;
    }

    /**
     * Get eremumota
     *
     * @return Eremumota
     */
    public function getEremumota()
    {
        return $this->eremumota;
    }

    /**
     * Set formula
     *
     * @param Formula $formula
     *
     * @return Eremua
     */
    public function setFormula(Formula $formula = null)
    {
        $this->formula = $formula;

        return $this;
    }

    /**
     * Get formula
     *
     * @return Formula
     */
    public function getFormula()
    {
        return $this->formula;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Eremua
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
