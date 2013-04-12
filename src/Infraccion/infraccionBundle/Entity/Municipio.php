<?php

namespace Infraccion\infraccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Municipio
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Infraccion\infraccionBundle\Entity\MunicipioRepository")
 *
 * @DoctrineAssert\UniqueEntity("codigo")
 * @DoctrineAssert\UniqueEntity("nombre")
 */
class Municipio
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
     * @Assert\Length(min = 1, max = 2)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=32)
     * @Assert\Length(min = 3, max = 32)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=64)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=64)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string", length=64)
     * @Assert\Length(min = 3, max = 64)
     */
    private $localidad;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_postal", type="string", length=15)
     * @Assert\Length(min = 4, max = 15)
     */
    private $codigoPostal;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_1_nombre", type="string", length=64)
     */
    private $cont1Nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_1_cargo", type="string", length=64)
     */
    private $cont1Cargo;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_1_celular", type="string", length=32)
     */
    private $cont1Celular;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_1_telfijo", type="string", length=32)
     */
    private $cont1Telfijo;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_1_email", type="string", length=64)
     */
    private $cont1Email;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_2_nombre", type="string", length=64, nullable=true)
     */
    private $cont2Nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_2_cargo", type="string", length=64, nullable=true)
     */
    private $cont2Cargo;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_2_celular", type="string", length=32, nullable=true)
     */
    private $cont2Celular;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_2_telfijo", type="string", length=32, nullable=true)
     */
    private $cont2Telfijo;

    /**
     * @var string
     *
     * @ORM\Column(name="cont_2_email", type="string", length=64, nullable=true)
     */
    private $cont2Email;


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
     * @return Municipio
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
     * @return Municipio
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
     * Set email
     *
     * @param string $email
     * @return Municipio
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Municipio
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Municipio
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Municipio
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
    
        return $this;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set codigoPostal
     *
     * @param string $codigoPostal
     * @return Municipio
     */
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;
    
        return $this;
    }

    /**
     * Get codigoPostal
     *
     * @return string 
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * Set cont1Nombre
     *
     * @param string $cont1Nombre
     * @return Municipio
     */
    public function setCont1Nombre($cont1Nombre)
    {
        $this->cont1Nombre = $cont1Nombre;
    
        return $this;
    }

    /**
     * Get cont1Nombre
     *
     * @return string 
     */
    public function getCont1Nombre()
    {
        return $this->cont1Nombre;
    }

    /**
     * Set cont1Cargo
     *
     * @param string $cont1Cargo
     * @return Municipio
     */
    public function setCont1Cargo($cont1Cargo)
    {
        $this->cont1Cargo = $cont1Cargo;
    
        return $this;
    }

    /**
     * Get cont1Cargo
     *
     * @return string 
     */
    public function getCont1Cargo()
    {
        return $this->cont1Cargo;
    }

    /**
     * Set cont1Celular
     *
     * @param string $cont1Celular
     * @return Municipio
     */
    public function setCont1Celular($cont1Celular)
    {
        $this->cont1Celular = $cont1Celular;
    
        return $this;
    }

    /**
     * Get cont1Celular
     *
     * @return string 
     */
    public function getCont1Celular()
    {
        return $this->cont1Celular;
    }

    /**
     * Set cont1Telfijo
     *
     * @param string $cont1Telfijo
     * @return Municipio
     */
    public function setCont1Telfijo($cont1Telfijo)
    {
        $this->cont1Telfijo = $cont1Telfijo;
    
        return $this;
    }

    /**
     * Get cont1Telfijo
     *
     * @return string 
     */
    public function getCont1Telfijo()
    {
        return $this->cont1Telfijo;
    }

    /**
     * Set cont1Email
     *
     * @param string $cont1Email
     * @return Municipio
     */
    public function setCont1Email($cont1Email)
    {
        $this->cont1Email = $cont1Email;
    
        return $this;
    }

    /**
     * Get cont1Email
     *
     * @return string 
     */
    public function getCont1Email()
    {
        return $this->cont1Email;
    }

    /**
     * Set cont2Nombre
     *
     * @param string $cont2Nombre
     * @return Municipio
     */
    public function setCont2Nombre($cont2Nombre)
    {
        $this->cont2Nombre = $cont2Nombre;
    
        return $this;
    }

    /**
     * Get cont2Nombre
     *
     * @return string 
     */
    public function getCont2Nombre()
    {
        return $this->cont2Nombre;
    }

    /**
     * Set cont2Cargo
     *
     * @param string $cont2Cargo
     * @return Municipio
     */
    public function setCont2Cargo($cont2Cargo)
    {
        $this->cont2Cargo = $cont2Cargo;
    
        return $this;
    }

    /**
     * Get cont2Cargo
     *
     * @return string 
     */
    public function getCont2Cargo()
    {
        return $this->cont2Cargo;
    }

    /**
     * Set cont2Celular
     *
     * @param string $cont2Celular
     * @return Municipio
     */
    public function setCont2Celular($cont2Celular)
    {
        $this->cont2Celular = $cont2Celular;
    
        return $this;
    }

    /**
     * Get cont2Celular
     *
     * @return string 
     */
    public function getCont2Celular()
    {
        return $this->cont2Celular;
    }

    /**
     * Set cont2Telfijo
     *
     * @param string $cont2Telfijo
     * @return Municipio
     */
    public function setCont2Telfijo($cont2Telfijo)
    {
        $this->cont2Telfijo = $cont2Telfijo;
    
        return $this;
    }

    /**
     * Get cont2Telfijo
     *
     * @return string 
     */
    public function getCont2Telfijo()
    {
        return $this->cont2Telfijo;
    }

    /**
     * Set cont2Email
     *
     * @param string $cont2Email
     * @return Municipio
     */
    public function setCont2Email($cont2Email)
    {
        $this->cont2Email = $cont2Email;
    
        return $this;
    }

    /**
     * Get cont2Email
     *
     * @return string 
     */
    public function getCont2Email()
    {
        return $this->cont2Email;
    }
}
