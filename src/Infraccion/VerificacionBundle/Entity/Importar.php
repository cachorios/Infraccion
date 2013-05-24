<?php

namespace Infraccion\VerificacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Infraccion\VerificacionBundle\Validator as sisAssert;
use Symfony\Component\HttpFoundation\Request;


class Importar
{
    /**
     * @sisAssert\ImportarAutomotor()
     * @Assert\File(maxSize="16000000")
     */
    private $file;

    /**
     * @Assert\NotBlank()
     */
    private $columna = "A";

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^([0-9]+)$/" )
     */
    private $fila = 2;

    /**
     * Set imagen
     *
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get imagen
     *
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fila
     *
     */
    public function setFila($fila)
    {
        $this->fila = $fila;
    }

    /**
     * Get fila
     *
     */
    public function getFila()
    {
        return $this->fila;
    }

    /**
     * Set columna
     *
     */
    public function setColumna($columna)
    {
        $this->columna = $columna;
    }

    /**
     * Get columna
     *
     */
    public function getColumna()
    {
        return \strtolower($this->columna);
    }

    /**
     * Get toArray
     *
     */
    public function getColumnaArray()
    {
        $columna = array("dominio" => "A", "marca" => "B", "modelo" => "C", "dni" => "D", "cuit_cuil" => "E", "nombre" => "F", "domicilio" => "G", "codigo_postal" => "H", "provincia" => "I", "localidad" => "J");



        return $columna;
    }

}