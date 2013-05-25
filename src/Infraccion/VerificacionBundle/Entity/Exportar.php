<?php

namespace Infraccion\VerificacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;


class Exportar
{
    /**
     *
     */
    private $fechaInicio;

    /**
     *
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
        $fecha = new \DateTime('now');

        $this->fechaFinal = $fecha;
        $this->fechaInicio =  new \DateTime('-1 day'); //strtotime("$fecha -1 day") ;
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

}