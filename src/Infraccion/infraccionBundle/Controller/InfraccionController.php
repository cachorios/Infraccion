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

}