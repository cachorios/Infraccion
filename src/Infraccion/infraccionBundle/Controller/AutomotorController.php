<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 25/05/13
 * Time: 20:18
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\infraccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AutomotorController Extends Controller {
    public function domionioAjaxEditAction(){
        $id = $this->getRequest()->get("id");
        $entity = $this->getDoctrine()->getRepository("VerificacionBundle:Automotor")->find($id);


        return $this->render('InfraccionBundle:Automotor:automotor_edit.html.twig', array(
            'entity' => $entity

        ));

    }

}