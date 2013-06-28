<?php

namespace Infraccion\infraccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping\Index as Index;
use Infraccion\infraccionBundle\validator as acDom;
/**
 * Infraccion
 *
 * @ORM\Table(indexes={@Index(name="fichero_idx", columns={"municipio_id","ubicacion_id","tipo_infraccion_id","dominio","fecha"})})
 * @ORM\Entity(repositoryClass="Infraccion\infraccionBundle\Entity\InfraccionRepository")
 *
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
     *
     * @ORM\ManyToOne(targetEntity="Infraccion\infraccionBundle\Entity\Municipio")
     *
     */
    private $municipio;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Infraccion\infraccionBundle\Entity\Ubicacion")
     */
    private $ubicacion;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Infraccion\infraccionBundle\Entity\TipoInfraccion")
     */
    private $tipo_infraccion;


    /**
     * @var string
     *
     * @ORM\Column(name="dominio", type="string", length=6)
     * @Assert\Length(min = 6, max = 6)
     * @acDom\ConstrainsDominio
     */

    private $dominio;

    /**
     * @var datetime
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="foto1", type="string", length=255, nullable = true)
     */
    private $foto1;

    /**
     * @var string
     *
     * @ORM\Column(name="foto2", type="string", length=255, nullable = true)
     */
    private $foto2;

    /**
     * @var string
     *
     * @ORM\Column(name="foto3", type="string", length=255, nullable = true)
     */
    private $foto3;


    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable = true)
     */
    private $observacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="etapa", type="integer", nullable = false)
     */
    private $etapa;


    /**
     * @var integer
     * @ORM\Column(name="nro_infraccion", type="integer", nullable=true)
     */
    private $nro_infraccion;

    /**
     * @var datetime
     *
     * @ORM\Column(name="fecha_cedula", type="datetime", nullable=true)
     */

    private $fecha_cedula;
    /**
     * @ORM\ManyToOne(targetEntity="Infraccion\VerificacionBundle\Entity\Automotor", cascade={"persist"} )
     */
    private $automotor;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Infraccion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
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


        return null==$this->foto1 ?  "../../img/no-foto.png" : $this->foto1 ;
    }

    public function getFotoR1()
    {
        return $this->foto1 ;
    }
    public function getFotoR2()
    {
        return $this->foto2 ;
    }
    public function getFotoR3()
    {
        return $this->foto3 ;
    }
    /**
     * Set foto2
     *
     * @param string $foto2
     * @return Infraccion
     */
    public function setFoto2($foto2)
    {
        $this->foto2  = $foto2;
    
        return $this;
    }

    /**
     * Get foto2
     *
     * @return string 
     */
    public function getFoto2()
    {
        return null==$this->foto2 ?  "../../img/no-foto.png" : $this->foto2 ;
    }

    /**
     * Set foto3
     *
     * @param string $foto3
     * @return Infraccion
     */
    public function setFoto3($foto3)
    {
        $this->foto3 = $foto3;
    
        return $this;
    }

    /**
     * Get foto3
     *
     * @return string 
     */
    public function getFoto3()
    {
        return null==$this->foto3 ?  "../../img/no-foto.jpg" : $this->foto3 ;
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

    /**
     * Set municipio
     *
     * @param \Infraccion\infraccionBundle\Entity\Municipio $municipio
     * @return Infraccion
     */
    public function setMunicipio(\Infraccion\infraccionBundle\Entity\Municipio $municipio = null)
    {
        $this->municipio = $municipio;
    
        return $this;
    }

    /**
     * Get municipio
     *
     * @return \Infraccion\infraccionBundle\Entity\Municipio 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Set ubicacion
     *
     * @param \Infraccion\infraccionBundle\Entity\Ubicacion $ubicacion
     * @return Infraccion
     */
    public function setUbicacion(\Infraccion\infraccionBundle\Entity\Ubicacion $ubicacion = null)
    {
        $this->ubicacion = $ubicacion;
    
        return $this;
    }

    /**
     * Get ubicacion
     *
     * @return \Infraccion\infraccionBundle\Entity\Ubicacion 
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * Set tipo_infraccion
     *
     * @param \Infraccion\infraccionBundle\Entity\TipoInfraccion $tipoInfraccion
     * @return Infraccion
     */
    public function setTipoInfraccion(\Infraccion\infraccionBundle\Entity\TipoInfraccion $tipoInfraccion = null)
    {
        $this->tipo_infraccion = $tipoInfraccion;
    
        return $this;
    }

    /**
     * Get tipo_infraccion
     *
     * @return \Infraccion\infraccionBundle\Entity\TipoInfraccion 
     */
    public function getTipoInfraccion()
    {
        return $this->tipo_infraccion;
    }
//
//    /**
//     * Get Automotor
//     *
//     * @return \Infraccion\VerificacionBundle\Entity\Automotor
//     */
//    public function getAutomotor(){
//
//        return $this->automotor;
//    }
//
//    public function setAutomotor($auto){
//        $this->automotor = $auto;
//    }


    /**
     * Set automotor
     *
     * @param \Infraccion\VerificacionBundle\Entity\Automotor $automotor
     * @return Infraccion
     */
    public function setAutomotor(\Infraccion\VerificacionBundle\Entity\Automotor $automotor = null)
    {
        $this->automotor = $automotor;
    
        return $this;
    }

    /**
     * Get automotor
     *
     * @return \Infraccion\VerificacionBundle\Entity\Automotor 
     */
    public function getAutomotor()
    {
        return $this->automotor;
    }

    /**
     * Set nro_infraccion
     *
     * @param integer: $nroInfraccion
     * @return Infraccion
     */
    public function setNroInfraccion($nroInfraccion)
    {
        $this->nro_infraccion = $nroInfraccion;
    
        return $this;
    }

    /**
     * Get nro_infraccion
     *
     * @return integer:
     */
    public function getNroInfraccion()
    {
        return $this->nro_infraccion;
    }

    /**
     * Set fecha_cedula
     *
     * @param \DateTime $fechaCedula
     * @return Infraccion
     */
    public function setFechaCedula($fechaCedula)
    {
        $this->fecha_cedula = $fechaCedula;
    
        return $this;
    }

    /**
     * Get fecha_cedula
     *
     * @return \DateTime 
     */
    public function getFechaCedula()
    {
        return $this->fecha_cedula;
    }
}
