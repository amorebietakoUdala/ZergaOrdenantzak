<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Attribute\UdalaEgiaztatu;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use App\Repository\AtalaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Atala
 */
#[ExclusionPolicy("all")]
#[UdalaEgiaztatu(userFieldName: "udala_id")]
#[ORM\Entity(repositoryClass: AtalaRepository::class)]
#[ORM\Table(name: 'atala')]
#[ORM\Index(name: 'ordenantza_id_idx', columns: ['ordenantza_id'])]
class Atala implements \Stringable
{
    /**
     * @var integer
     */
    #[Expose()]
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kodea', type: 'string', length: 9, nullable: true)]
    private $kodea;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'kodea_real', type: 'string', length: 9, nullable: true)]
    private $kodea_prod;

    /**
     * @var string
     */
    #[ORM\Column(name: 'izenburuaeu', type: 'string', length: 255, nullable: true)]
    private $izenburuaeu;
    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'izenburuaeu_prod', type: 'string', length: 255, nullable: true)]
    private $izenburuaeu_prod;

    /**
     * @var string
     */
    #[ORM\Column(name: 'izenburuaes', type: 'string', length: 255, nullable: true)]
    private $izenburuaes;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'izenburuaes_prod', type: 'string', length: 255, nullable: true)]
    private $izenburuaes_prod;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'utsa', type: 'boolean', nullable: true)]
    private $utsa;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'utsa_prod', type: 'boolean', nullable: true)]
    private $utsa_prod;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'ezabatu', type: 'boolean', nullable: true)]
    private $ezabatu;

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
     * @var \Ordenantza
     */
    #[ORM\ManyToOne(targetEntity: Ordenantza::class, inversedBy: 'atalak')]
    #[ORM\JoinColumn(name: 'ordenantza_id', referencedColumnName: 'id')]
    private $ordenantza;

    /**
     * @var Collection $parrafoak
     *
     */
    #[ORM\OneToMany(targetEntity: Atalaparrafoa::class, mappedBy: 'atala', cascade: ['remove'])]
    #[ORM\OrderBy(['ordena' => 'ASC'])]
    protected $parrafoak;

    /**
     * @var Collection $azpiatalak
     *
     */
    #[Expose()]
    #[ORM\OrderBy(['kodea' => 'ASC'])]
    #[ORM\OneToMany(targetEntity: Azpiatala::class, mappedBy: 'atala', cascade: ['remove'])]
    protected $azpiatalak;

    /**
     * @var Udala
     */
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;

    public function __construct()
    {
        $this->parrafoak = new ArrayCollection();
        $this->azpiatalak = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function __toString(): string
    {
        return $this->getKodea() . " - " . $this->getIzenburuaeu();
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
     * Set kodea
     *
     * @param string $kodea
     *
     * @return Atala
     */
    public function setKodea($kodea)
    {
        $this->kodea = $kodea;

        return $this;
    }

    /**
     * Get kodea
     *
     * @return string
     */
    public function getKodea()
    {
        return $this->kodea;
    }

    /**
     * Set izenburuaeu
     *
     * @param string $izenburuaeu
     *
     * @return Atala
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
     * @return Atala
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
     * Set utsa
     *
     * @param boolean $utsa
     *
     * @return Atala
     */
    public function setUtsa($utsa)
    {
        $this->utsa = $utsa;

        return $this;
    }

    /**
     * Get utsa
     *
     * @return boolean
     */
    public function getUtsa()
    {
        return $this->utsa;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Atala
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
     * @return Atala
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
     * Set ordenantza
     *
     * @param Ordenantza $ordenantza
     *
     * @return Atala
     */
    public function setOrdenantza(Ordenantza $ordenantza = null)
    {
        $this->ordenantza = $ordenantza;

        return $this;
    }

    /**
     * Get ordenantza
     *
     * @return Ordenantza
     */
    public function getOrdenantza()
    {
        return $this->ordenantza;
    }

    /**
     * Add parrafoak
     *
     * @param Atalaparrafoa $parrafoak
     *
     * @return Atala
     */
    public function addParrafoak(Atalaparrafoa $parrafoak)
    {
        $this->parrafoak[] = $parrafoak;

        return $this;
    }

    /**
     * Remove parrafoak
     *
     * @param Atalaparrafoa $parrafoak
     */
    public function removeParrafoak(Atalaparrafoa $parrafoak)
    {
        $this->parrafoak->removeElement($parrafoak);
    }

    /**
     * Get parrafoak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParrafoak()
    {
        return $this->parrafoak;
    }

    /**
     * Add azpiatalak
     *
     * @param Azpiatala $azpiatalak
     *
     * @return Atala
     */
    public function addAzpiatalak(Azpiatala $azpiatalak)
    {
        $this->azpiatalak[] = $azpiatalak;

        return $this;
    }

    /**
     * Remove azpiatalak
     *
     * @param Azpiatala $azpiatalak
     */
    public function removeAzpiatalak(Azpiatala $azpiatalak)
    {
        $this->azpiatalak->removeElement($azpiatalak);
    }

    /**
     * Get azpiatalak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAzpiatalak()
    {
        return $this->azpiatalak;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Atala
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

    /**
     * Set kodeaProd
     *
     * @param string $kodeaProd
     *
     * @return Atala
     */
    public function setKodeaProd($kodeaProd)
    {
        $this->kodea_prod = $kodeaProd;

        return $this;
    }

    /**
     * Get kodeaProd
     *
     * @return string
     */
    public function getKodeaProd()
    {
        return $this->kodea_prod;
    }

    /**
     * Set izenburuaeuProd
     *
     * @param string $izenburuaeuProd
     *
     * @return Atala
     */
    public function setIzenburuaeuProd($izenburuaeuProd)
    {
        $this->izenburuaeu_prod = $izenburuaeuProd;

        return $this;
    }

    /**
     * Get izenburuaeuProd
     *
     * @return string
     */
    public function getIzenburuaeuProd()
    {
        return $this->izenburuaeu_prod;
    }

    /**
     * Set izenburuaesProd
     *
     * @param string $izenburuaesProd
     *
     * @return Atala
     */
    public function setIzenburuaesProd($izenburuaesProd)
    {
        $this->izenburuaes_prod = $izenburuaesProd;

        return $this;
    }

    /**
     * Get izenburuaesProd
     *
     * @return string
     */
    public function getIzenburuaesProd()
    {
        return $this->izenburuaes_prod;
    }

    /**
     * Set utsaProd
     *
     * @param boolean $utsaProd
     *
     * @return Atala
     */
    public function setUtsaProd($utsaProd)
    {
        $this->utsa_prod = $utsaProd;

        return $this;
    }

    /**
     * Get utsaProd
     *
     * @return boolean
     */
    public function getUtsaProd()
    {
        return $this->utsa_prod;
    }

    /**
     * Set ezabatu
     *
     * @param boolean $ezabatu
     *
     * @return Atala
     */
    public function setEzabatu($ezabatu)
    {
        $this->ezabatu = $ezabatu;

        return $this;
    }

    /**
     * Get ezabatu
     *
     * @return boolean
     */
    public function getEzabatu()
    {
        return $this->ezabatu;
    }
}
