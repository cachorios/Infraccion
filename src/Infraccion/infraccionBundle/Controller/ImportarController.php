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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;

class ImportarController extends Controller
{

    public function importarAction()
    {
        //cargar carpetas
        $reg = $this->getDirectories();
        return $this->render('InfraccionBundle:Importar:importar.html.twig', array(
            'reg' => $reg,
        ));
    }

    private function getDirectories()
    {
        $ret = array();
        $finder = new Finder();
        $finder->directories()->depth(0)->in($this->container->getParameter("infraccion.unproccess.dir"));

        foreach ($finder as $dir) {
            $aux = $this->getDetalle($dir->getFilename());
            if($aux)
                $ret[] = $aux;
        }
        return $ret;
    }

    private function getDetalle($dir_name)
    {

        /**
         * 13010320130125
         * 1,2: Empresa nn
         * 3,2: Ubicacion nn
         * 5,2: Tipo de infraccion nn
         * 7:8: fecha aaaammdd
         */
        if( strlen($dir_name) != 14 )
            return null;

        $em = $this->getDoctrine()->getManager();
        $ret = array();

        $ret[0] = substr($dir_name, 0, 2);
        $ret[1] = $em->getRepository("InfraccionBundle:Municipio")->findBy(array("codigo" => $ret[0]))[0]->getNombre();

        $ret[2] = substr($dir_name, 2, 2);
        $ret[3] = $em->getRepository("InfraccionBundle:Ubicacion")->findBy(array("codigo" => $ret[2]))[0]->getReferencia();

        $ret[4] = substr($dir_name, 4, 2);
        $ret[5] = $em->getRepository("InfraccionBundle:TipoInfraccion")->findBy(array("codigo" => $ret[4]))[0]->getNombre();

        $ret[6] = substr($dir_name, 6);
        $ret[7] = $dir_name;


        return $ret;

    }


    public function importarCarpetaAction()
    {
        $carpeta = $this->getRequest()->get("carpeta");
        $em = $this->getDoctrine()->getManager();
        $finder = new Finder();
        $finder->files()
            ->depth(0)
            ->sortByName()
            ->in($this->container->getParameter("infraccion.unproccess.dir") . $carpeta);

        //verificar carpeta destino, para no tenes que hacelo en cada recorrida del bucle
        $this->verificarDestino($carpeta);
        $msg = "";
        $reg = null;

        $oldFile = "empty";
        foreach ($finder as $file) {
            if ($oldFile == substr($file->getFileName(), 0, 20) && $reg ) {
                $foto = substr($file->getFileName(), 26, 1);
                $msgFoto = "";
                if ($foto == "1"){
                    if($reg->getFoto1() == null )
                        $reg->setFoto1($file->getFileName());
                    else
                        $msgFoto = "La foto1 ya fue asignada ".$file;
                }
                if ($foto == "2"){
                    if($reg->getFoto2() == null )
                        $reg->setFoto2($file->getFileName());
                    else
                        $msgFoto = "La foto2 ya fue asignada ".$file;
                }
                if ($foto == "3"){
                    if($reg->getFoto3() == null )
                        $reg->setFoto3($file->getFileName());
                    else
                        $msgFoto = "La foto3 ya fue asignada ".$file;
                }
                $msg .= $msgFoto;
                $this->moveFoto($carpeta, $file->getFilename());

            } else {
                if($reg){
                    $em->persist($reg);
                    $em->flush();
                    $reg = null;
                }

                $oldFile = substr($file->getFileName(), 0, 20);
                try {
                    $reg = $this->generarRegistro($file->getFilename());
                    $this->moveFoto($carpeta, $file->getFilename());
                } catch (Exception $e) {
                    $reg = null;
                    $msg += $e->getMessage();

                }
            }
        }
        if($reg){
            $em->persist($reg);
            $em->flush();
        }

        // si la carpeta quedo vacia, borrarlo
        rmdir($this->container->getParameter("infraccion.unproccess.dir").$carpeta);

        $res = new Response();
        $res->getContent($msg);
        return $res;

    }


    /**
     * generarRegistro
     * @return Infraccion
     * $file
     * 250103AAA145201305251239112.jpg
     * 1,2: Empresa nn
     * 3,2: Ubicacion nn
     * 5,2: Tipo de infraccion nn
     * 7,6: Dominio
     *13,8: fecha
     *21,6: Hora
     *27,1: Nro de foto
     **/
    private function generarRegistro($file)
    {
        $empresa = substr($file, 0, 2);
        $ubicacion = substr($file, 2, 2);
        $tipoInfraccion = substr($file, 4, 2);
        $dominio = substr($file, 6, 6);
        $fecha = substr($file, 12, 8);
        $hora = substr($file, 20, 6);
        $foto = substr($file, 26, 1);


        $dt = new \DateTime();
        $dt->setDate(substr($fecha, 0, 4), substr($fecha, 4, 2), substr($fecha, 6, 2));
        $dt->setTime(substr($hora, 0, 2), substr($hora, 2, 2), substr($hora, 4, 2));
        //verificar que no este el registro
        $em = $this->getDoctrine()->getManager();
        $reg = $em->getRepository("InfraccionBundle:Infraccion")->getInfraccionByDatos($empresa, $ubicacion, $tipoInfraccion, $dominio, $dt);

        if ($reg) {
            throw(new Exception("El registro existe - $empresa,$ubicacion, $tipoInfraccion, $dominio, $fecha, $hora  "));
        } else {
            $reg = new Infraccion();
            $reg->setMunicipio( $em->getRepository("InfraccionBundle:Municipio")->findOneBy(array("codigo" => $empresa)));
            $reg->setUbicacion($em->getRepository("InfraccionBundle:Ubicacion")->findOneBy(array("codigo" => $ubicacion)));
            $reg->setTipoInfraccion($em->getRepository("InfraccionBundle:TipoInfraccion")->findOneBy(array("codigo" => $tipoInfraccion)));
            $reg->setDominio($dominio);
            $reg->setFecha($dt);
            $reg->setEtapa(1);

            if ($foto == "1")
                $reg->setFoto1($file);
            if ($foto == "2")
                $reg->setFoto2($file);
            if ($foto == "3")
                $reg->setFoto3($file);

        }
        return $reg;
    }

    private function verificarDestino($carpeta){
        if(!is_dir( $this->container->getParameter("infraccion.infracciones.dir").substr($carpeta,6,6))){
            mkdir($this->container->getParameter("infraccion.infracciones.dir").substr($carpeta,6,6));
        }
    }

    private function moveFoto($carpeta,$file){
        if (copy( $this->container->getParameter("infraccion.unproccess.dir").$carpeta."/".$file,$this->container->getParameter("infraccion.infracciones.dir").substr($carpeta,6,6)."/".$file)) {
            unlink($this->container->getParameter("infraccion.unproccess.dir").$carpeta."/".$file);
        }else{
            ld("no pudo mover $file");
        }

    }
}