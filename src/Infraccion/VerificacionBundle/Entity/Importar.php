<?php

namespace Infraccion\VerificacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Infraccion\VerificacionBundle\Validator as sisAssert;
use Symfony\Component\HttpFoundation\Request;


class Importar
{
    /**
     * @sisAssert\ImportarAutomotor()
     * @Assert\File(maxSize="6000000")
     */
    private $file;

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


}