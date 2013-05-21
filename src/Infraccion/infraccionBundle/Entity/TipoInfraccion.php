<?php

namespace Infraccion\infraccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
<<<<<<< HEAD
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
=======
>>>>>>> modelo

/**
 * TipoInfraccion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Infraccion\infraccionBundle\Entity\TipoInfraccionRepository")
<<<<<<< HEAD
 * @DoctrineAssert\UniqueEntity("codigo")
 * @DoctrineAssert\UniqueEntity("nombre")
=======
>>>>>>> modelo
 */
class TipoInfraccion
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
     * @var integer
     *
     * @ORM\Column(name="codigo", type="integer")
<<<<<<< HEAD
     * @Assert\Length(min = 1, max = 2)
=======
>>>>>>> modelo
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=64)
<<<<<<< HEAD
     * @Assert\Length(min = 3, max = 64)
=======
>>>>>>> modelo
     */
    private $nombre;

    /**
     * @var string
     *
<<<<<<< HEAD
     * @ORM\Column(name="observacion", type="text", nullable = true)


=======
     * @ORM\Column(name="observacion", type="text")
>>>>>>> modelo
     */
    private $observacion;


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
     * Set codigo
     *
     * @param integer $codigo
     * @return TipoInfraccion
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TipoInfraccion
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return TipoInfraccion
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
}
