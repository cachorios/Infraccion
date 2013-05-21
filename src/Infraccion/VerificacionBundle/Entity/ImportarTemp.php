<?php

namespace Infraccion\VerificacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Infraccion\VerificacionBundle\Validator as sisAssert;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Importar
 */
class Importar1
{


    private $file;

    public $nombre;

    /**
     * Set
     *
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get
     *
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * Set
     *
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get
     *
     */
    public function getNombre()
    {
        return $this->nombre;
    }
//    public function getAbsolutePath()
//    {
//        return null === $this->path
//            ? null
//            : $this->getUploadRootDir().'/'.$this->path;
//    }
//
//    protected function getUploadRootDir()
//    {
//        // la ruta absoluta del directorio donde se deben
//        // guardar los archivos cargados
//        %kernel.root_dir%"/../web/uploads/procesar_excel/"
//        return $this->container->getParameter('directorio.importa');
//    }
//    public function upload()
//    {
//        // la propiedad file puede estar vacía si el campo no es obligatorio
//        if (null === $this->file) {
//            return;
//        }
//
//        // aquí usa el nombre de archivo original pero lo debes
//        // sanear al menos para evitar cualquier problema de seguridad
//
//        // lo mueve tomando el directorio destino y luego
//        // el nombre de archivo al cual moverlo
//        $this->file->move(
//            $this->getUploadRootDir(),
//            $this->file->getClientOriginalName()
//        );
//
////        // configura la propiedad 'path' al nombre de archivo en que lo guardaste
//    $this->nombre = $this->file->getClientOriginalName();
//
//    // limpia la propiedad «file» ya que no la necesitas más
//    $this->file = null;
//}

}
