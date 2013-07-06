<?php

namespace Infraccion\VerificacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @Assert\Callback(methods={"esFechaValido"})
 */
class Exportar
{
    /**
     *
     */
    /**
     * @var \DateTime
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     */
    private $fechaFinal;

    /**
     *
     */
    private $usarFecha = false;

    /**
     *
     */
    private $usarNull = true;




    public function __construct()
    {

        $this->fechaInicio =  new \DateTime('previous monday');
        $this->fechaFinal = new \DateTime('now');

    }
    /**
     * Set fechaInicio
     *
     * @param datetime $fechaInicio
     * @return fechaInicio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return datetime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFinal
     *
     * @param datetime $fechaFinal
     * @return fechaFinal
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return datetime
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * Set usarFecha
     *
     * @param boolean $usarFecha
     * @return usarFecha
     */
    public function setUsarFecha($usarFecha)
    {
        $this->usarFecha = $usarFecha;

        return $this;
    }

    /**
     * Get usarFecha
     *
     * @return boolean
     */
    public function getUsarFecha()
    {
        return $this->usarFecha;
    }

    /**
     * Set usarNull
     *
     * @param boolean $usarNull
     * @return usarNull
     */
    public function setUsarNull($usarNull)
    {
        $this->usarNull = $usarNull;

        return $this;
    }

    /**
     * Get usarNull
     *
     * @return boolean
     */
    public function getUsarNull()
    {
        return $this->usarNull;
    }

    public function esFechaValido(ExecutionContext $context)
    {
        if($this->usarFecha ==true) {
            if($this->getfechaInicio()->format('U') > $this->getFechaFinal()->format('U')){
                $context->addViolationAtSubPath('dni', 'La fecha de inicio no puede ser mayor a la fecha de final.',array(),null);

            }

        }
        return;
    }

}