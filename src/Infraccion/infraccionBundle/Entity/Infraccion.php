<?php

namespace Infraccion\infraccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Infraccion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Infraccion\infraccionBundle\Entity\InfraccionRepository")
 */
class Infraccion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="municipio", type="string", length=255)
     */
    private $municipio;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_infraccion", type="string", length=255)
     */
    private $tipoInfraccion;

    /**
     * @var string
     *
     * @ORM\Column(name="foto1", type="string", length=255)
     */
    private $foto1;

    /**
     * @var string
     *
     * @ORM\Column(name="foto2", type="string", length=255)
     */
    private $foto2;

    /**
     * @var string
     *
     * @ORM\Column(name="dominio", type="string", length=6)
     */
    private $dominio;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text")
     */
    private $observacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="etapa", type="integer")
     */
    private $etapa;


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
     * Set municipio
     *
     * @param string $municipio
     * @return Infraccion
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    
        return $this;
    }

    /**
     * Get municipio
     *
     * @return string 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Set tipoInfraccion
     *
     * @param string $tipoInfraccion
     * @return Infraccion
     */
    public function setTipoInfraccion($tipoInfraccion)
    {
        $this->tipoInfraccion = $tipoInfraccion;
    
        return $this;
    }

    /**
     * Get tipoInfraccion
     *
     * @return string 
     */
    public function getTipoInfraccion()
    {
        return $this->tipoInfraccion;
    }

    /**
     * Set foto1
     *
     * @param string $foto1
     * @return Infraccion
     */
    public function setFoto1($foto1)
    {
        $this->foto1 = $foto1;
    
        return $this;
    }

    /**
     * Get foto1
     *
     * @return string 
     */
    public function getFoto1()
    {
        return $this->foto1;
    }

    /**
     * Set foto2
     *
     * @param string $foto2
     * @return Infraccion
     */
    public function setFoto2($foto2)
    {
        $this->foto2 = $foto2;
    
        return $this;
    }

    /**
     * Get foto2
     *
     * @return string 
     */
    public function getFoto2()
    {
        return $this->foto2;
    }

    /**
     * Set dominio
     *
     * @param string $dominio
     * @return Infraccion
     */
    public function setDominio($dominio)
    {
        $this->dominio = $dominio;
    
        return $this;
    }

    /**
     * Get dominio
     *
     * @return string 
     */
    public function getDominio()
    {
        return $this->dominio;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return Infraccion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    
        return $this;
    }

    /**
     * Get observacion
     *
     * @return string 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set etapa
     *
     * @param integer $etapa
     * @return Infraccion
     */
    public function setEtapa($etapa)
    {
        $this->etapa = $etapa;
    
        return $this;
    }

    /**
     * Get etapa
     *
     * @return integer 
     */
    public function getEtapa()
    {
        return $this->etapa;
    }
}
