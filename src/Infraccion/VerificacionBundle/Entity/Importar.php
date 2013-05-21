<?php

namespace Infraccion\VerificacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Infraccion\VerificacionBundle\Validator as sisAssert;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Importar
{
    /**
     */
    private $archivo;

    /**
     */
    private $archivo_nombreReal;

    /**
     */
    private $dominio;

    /**
     */
    private $marca;

    /**
     */
    private $modelo;

    /**
     */
    private $dni;

    /**
     */
    private $cuitCuil;

    /**
     */
    private $nombre;

    /**
     */
    private $domicilio;

    /**
     */
    private $codigoPostal;

    /**
     */
    private $provincia;

    /**
     */
    private $localidad;

    /**
     */
    private $activate_dominio;

    /**
     */
    private $activate_marca;

    /**
     */
    private $activate_modelo;

    /**
     */
    private $activate_dni;

    /**
     */
    private $activate_cuitCuil;

    /**
     */
    private $activate_nombre;

    /**
     */
    private $activate_domicilio;

    /**
     */
    private $activate_codigoPostal;

    /**
     */
    private $activate_provincia;

    /**
     */
    private $activate_localidad;

}
