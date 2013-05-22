<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 21/05/13
 * Time: 11:27
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\infraccionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Infraccion\infraccionBundle\Entity\Infraccion;

class InfraccionController extends  Controller {

    public function importarAction() {
        //cargar carpetas
        $reg = $this->getDirectories();
        return $this->render('InfraccionBundle:Infraccion:importar.html.twig', array(
            'reg' => $reg,
        ));
    }

    private function getDirectories(){
        $ret = array();
        $finder = new Finder();
        $finder->directories()->depth(0)->in($this->container->getParameter("infraccion.unproccess.dir"));

        foreach($finder as $dir){
            $ret[] = $this->getDetalle($dir->getFilename());
        }
        return $ret;
    }

    private function getDetalle($dir_name){
        $em = $this->getDoctrine()->getManager();
        $ret = array();
        /**
         * 13010320130125
         * 1,2: Empresa nn
         * 3,2: Ubicacion nn
         * 5,2: Tipo de infraccion nn
         * 7:8: fecha aaaammdd
         */
        $ret[0] = substr($dir_name,0,2);
        $ret[1] = $em->getRepository("InfraccionBundle:Municipio")->findBy(array("codigo" => $ret[0]))[0]->getNombre();

        $ret[2] = substr($dir_name,2,2);
        $ret[3] = $em->getRepository("InfraccionBundle:Ubicacion")->findBy(array("codigo" => $ret[2]))[0]->getReferencia();

        $ret[4] = substr($dir_name,4,2);
        $ret[5] = $em->getRepository("InfraccionBundle:TipoInfraccion")->findBy(array("codigo" => $ret[4]))[0]->getNombre();

        $ret[6] = substr($dir_name,6);
        $ret[7] = $dir_name;


        return $ret;

    }


    public function importarCarpetaAction($carpeta){
        $finder = new Finder();
        $finder->files()
            ->depth(0)
            ->sortByName()
            ->in($this->container->getParameter("infraccion.unproccess.dir").$carpeta)
        ;

        foreach($finder as $file){
            $this->generarRegistro($file->getFilename());
        }
    }

    private function generarRegistro($file){
        /**
         * 25 01 03 AAA145 201305251239112.jpg
         * 1,2: Empresa nn
         * 3,2: Ubicacion nn
         * 5,2: Tipo de infraccion nn
         * 7,6: Dominio
         *13,8: fecha
         *21,6: Hora
         *27,1: Nro de foto
         **/
        $empresa = substr($file,0,2);
        $ubicacion = substr($file,2,2);
        $tipoInfraccion = substr($file,4,2);
        $dominio = substr($file,6,6);
        $fecha  = substr($file,12,8);
        $hora = substr($file,20,6);
        $foto = substr($file,26,1);

        //verificar que no este el registro
        $em = $this->getDoctrine()->getManager();
        $em->getRepository("InfraccionBundle:Infraccion")->findBy("");

    }

}