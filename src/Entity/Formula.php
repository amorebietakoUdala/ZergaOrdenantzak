<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;

/**
 * Formula
 *
 */
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Entity]
#[ORM\Table(name: 'formula')]
#[ORM\Index(name: 'atala_id_idx', columns: ['atala_id'])]
class Formula implements \Stringable
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
    #[ORM\Column(name: 'izenburuaeu', type: 'text', length: 65535, nullable: true)]
    private $izenburuaeu;

    /**
     * @var string
     */
    #[ORM\Column(name: 'izenburuaes', type: 'text', length: 65535, nullable: true)]
    private $izenburuaes;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kodeajs', type: 'text', length: 65535, nullable: true)]
    private $kodeajs;

    /**
     * @var string
     */
    #[ORM\Column(name: 'emaitzahtml', type: 'text', length: 65535, nullable: true)]
    private $emaitzahtml;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private $createdAt;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private $updatedAt;

    /**
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     * ***** ERLAZIOAK
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     */
    /**
     * @var \Atala
     */
    #[ORM\ManyToOne(targetEntity: Atala::class)]
    #[ORM\JoinColumn(name: 'atala_id', referencedColumnName: 'id')]
    private $atala;

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
        return $this->getIzenburuaeu();
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
     * Set izenburuaeu
     *
     * @param string $izenburuaeu
     *
     * @return Formula
     */
    public function setIzenburuaeu($izenburuaeu)
    {
        $this->izenburuaeu = $izenburuaeu;

        return $this;
    }

    /**
     * Get izenburuaeu
     *
     * @return string
     */
    public function getIzenburuaeu()
    {
        return $this->izenburuaeu;
    }

    /**
     * Set izenburuaes
     *
     * @param string $izenburuaes
     *
     * @return Formula
     */
    public function setIzenburuaes($izenburuaes)
    {
        $this->izenburuaes = $izenburuaes;

        return $this;
    }

    /**
     * Get izenburuaes
     *
     * @return string
     */
    public function getIzenburuaes()
    {
        return $this->izenburuaes;
    }

    /**
     * Set kodeajs
     *
     * @param string $kodeajs
     *
     * @return Formula
     */
    public function setKodeajs($kodeajs)
    {
        $this->kodeajs = $kodeajs;

        return $this;
    }

    /**
     * Get kodeajs
     *
     * @return string
     */
    public function getKodeajs()
    {
        return $this->kodeajs;
    }

    /**
     * Set emaitzahtml
     *
     * @param string $emaitzahtml
     *
     * @return Formula
     */
    public function setEmaitzahtml($emaitzahtml)
    {
        $this->emaitzahtml = $emaitzahtml;

        return $this;
    }

    /**
     * Get emaitzahtml
     *
     * @return string
     */
    public function getEmaitzahtml()
    {
        return $this->emaitzahtml;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Formula
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Formula
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set atala
     *
     * @param Atala $atala
     *
     * @return Formula
     */
    public function setAtala(Atala $atala = null)
    {
        $this->atala = $atala;

        return $this;
    }

    /**
     * Get atala
     *
     * @return Atala
     */
    public function getAtala()
    {
        return $this->atala;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Formula
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
