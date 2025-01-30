<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use App\Repository\KontzeptuaRepository;

/**
 * Kontzeptua
 *
 */
#[ExclusionPolicy("all")]
#[ORM\Entity(repositoryClass: KontzeptuaRepository::class)]
#[ORM\Table(name: 'kontzeptua')]
#[ORM\Index(name: 'azpiatala_id_idx', columns: ['azpiatala_id'])]
#[ORM\Index(name: 'baldintza_id_idx', columns: ['baldintza_id'])]
#[ORM\Index(name: 'kontzeptumota_id_idx', columns: ['kontzeptumota_id'])]
class Kontzeptua
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
    #[ORM\Column(name: 'kodea', type: 'string', length: 9, nullable: true)]
    private $kodea;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'kodea_prod', type: 'string', length: 9, nullable: true)]
    private $kodea_prod;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kontzeptuaeu', type: 'text', length: 65535, nullable: true)]
    private $kontzeptuaeu;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'kontzeptuaeu_prod', type: 'text', length: 65535, nullable: true)]
    private $kontzeptuaeu_prod;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kontzeptuaes', type: 'text', length: 65535, nullable: true)]
    private $kontzeptuaes;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'kontzeptuaes_prod', type: 'text', length: 65535, nullable: true)]
    private $kontzeptuaes_prod;

    /**
     * @var string
     */
    #[ORM\Column(name: 'kopurua', type: 'string', length: 50, nullable: true)]
    private $kopurua;

    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'kopurua_prod', type: 'string', length: 50, nullable: true)]
    private $kopurua_prod;

    /**
     * @var string
     */
    #[ORM\Column(name: 'unitatea', type: 'string', length: 50, nullable: true)]
    private $unitatea;
    
    /**
     * @var string
     */
    #[Expose()]
    #[ORM\Column(name: 'unitatea_prod', type: 'string', length: 50, nullable: true)]
    private $unitatea_prod;

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
     * @var Azpiatala
     */
    #[ORM\ManyToOne(targetEntity: Azpiatala::class, inversedBy: 'kontzeptuak')]
    #[ORM\JoinColumn(name: 'azpiatala_id', referencedColumnName: 'id')]
    private $azpiatala;

    /**
     * @var Baldintza
     */
    #[ORM\ManyToOne(targetEntity: Baldintza::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'baldintza_id', referencedColumnName: 'id')]
    private $baldintza;

    /**
     * @var Kontzeptumota
     */
    #[ORM\ManyToOne(targetEntity: Kontzeptumota::class)]
    #[ORM\JoinColumn(name: 'kontzeptumota_id', referencedColumnName: 'id')]
    private $kontzeptumota;

    /**
     * @var Udala
     */
    #[ORM\ManyToOne(targetEntity: Udala::class)]
    private $udala;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->getKodea();
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
     * @return Kontzeptua
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
     * Set kontzeptuaeu
     *
     * @param string $kontzeptuaeu
     *
     * @return Kontzeptua
     */
    public function setKontzeptuaeu($kontzeptuaeu)
    {
        $this->kontzeptuaeu = $kontzeptuaeu;

        return $this;
    }

    /**
     * Get kontzeptuaeu
     *
     * @return string
     */
    public function getKontzeptuaeu()
    {
        return $this->kontzeptuaeu;
    }

    /**
     * Set kontzeptuaes
     *
     * @param string $kontzeptuaes
     *
     * @return Kontzeptua
     */
    public function setKontzeptuaes($kontzeptuaes)
    {
        $this->kontzeptuaes = $kontzeptuaes;

        return $this;
    }

    /**
     * Get kontzeptuaes
     *
     * @return string
     */
    public function getKontzeptuaes()
    {
        return $this->kontzeptuaes;
    }

    /**
     * Set kopurua
     *
     * @param string $kopurua
     *
     * @return Kontzeptua
     */
    public function setKopurua($kopurua)
    {
        $this->kopurua = $kopurua;

        return $this;
    }

    /**
     * Get kopurua
     *
     * @return string
     */
    public function getKopurua()
    {
        return $this->kopurua;
    }

    /**
     * Set unitatea
     *
     * @param string $unitatea
     *
     * @return Kontzeptua
     */
    public function setUnitatea($unitatea)
    {
        $this->unitatea = $unitatea;

        return $this;
    }

    /**
     * Get unitatea
     *
     * @return string
     */
    public function getUnitatea()
    {
        return $this->unitatea;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Kontzeptua
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
     * @return Kontzeptua
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
     * Set azpiatala
     *
     * @param Azpiatala $azpiatala
     *
     * @return Kontzeptua
     */
    public function setAzpiatala(Azpiatala $azpiatala = null)
    {
        $this->azpiatala = $azpiatala;

        return $this;
    }

    /**
     * Get azpiatala
     *
     * @return Azpiatala
     */
    public function getAzpiatala()
    {
        return $this->azpiatala;
    }

    /**
     * Set baldintza
     *
     * @param Baldintza $baldintza
     *
     * @return Kontzeptua
     */
    public function setBaldintza(Baldintza $baldintza = null)
    {
        $this->baldintza = $baldintza;

        return $this;
    }

    /**
     * Get baldintza
     *
     * @return Baldintza
     */
    public function getBaldintza()
    {
        return $this->baldintza;
    }

    /**
     * Set kontzeptumota
     *
     * @param Kontzeptumota $kontzeptumota
     *
     * @return Kontzeptua
     */
    public function setKontzeptumota(Kontzeptumota $kontzeptumota = null)
    {
        $this->kontzeptumota = $kontzeptumota;

        return $this;
    }

    /**
     * Get kontzeptumota
     *
     * @return Kontzeptumota
     */
    public function getKontzeptumota()
    {
        return $this->kontzeptumota;
    }

    /**
     * Set udala
     *
     * @param Udala $udala
     *
     * @return Kontzeptua
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
     * @return Kontzeptua
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
     * Set kontzeptuaeuProd
     *
     * @param string $kontzeptuaeuProd
     *
     * @return Kontzeptua
     */
    public function setKontzeptuaeuProd($kontzeptuaeuProd)
    {
        $this->kontzeptuaeu_prod = $kontzeptuaeuProd;

        return $this;
    }

    /**
     * Get kontzeptuaeuProd
     *
     * @return string
     */
    public function getKontzeptuaeuProd()
    {
        return $this->kontzeptuaeu_prod;
    }

    /**
     * Set kontzeptuaesProd
     *
     * @param string $kontzeptuaesProd
     *
     * @return Kontzeptua
     */
    public function setKontzeptuaesProd($kontzeptuaesProd)
    {
        $this->kontzeptuaes_prod = $kontzeptuaesProd;

        return $this;
    }

    /**
     * Get kontzeptuaesProd
     *
     * @return string
     */
    public function getKontzeptuaesProd()
    {
        return $this->kontzeptuaes_prod;
    }

    /**
     * Set kopuruaProd
     *
     * @param string $kopuruaProd
     *
     * @return Kontzeptua
     */
    public function setKopuruaProd($kopuruaProd)
    {
        $this->kopurua_prod = $kopuruaProd;

        return $this;
    }

    /**
     * Get kopuruaProd
     *
     * @return string
     */
    public function getKopuruaProd()
    {
        return $this->kopurua_prod;
    }

    /**
     * Set unitateaProd
     *
     * @param string $unitateaProd
     *
     * @return Kontzeptua
     */
    public function setUnitateaProd($unitateaProd)
    {
        $this->unitatea_prod = $unitateaProd;

        return $this;
    }

    /**
     * Get unitateaProd
     *
     * @return string
     */
    public function getUnitateaProd()
    {
        return $this->unitatea_prod;
    }

    /**
     * Set ezabatu
     *
     * @param boolean $ezabatu
     *
     * @return Kontzeptua
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
