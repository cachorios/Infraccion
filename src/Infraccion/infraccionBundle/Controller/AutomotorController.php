<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 25/05/13
 * Time: 20:18
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\infraccionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Infraccion\VerificacionBundle\Form\AutomotorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AutomotorController Extends Controller {
    public function domionioAjaxEditAction(){
        $id = $this->getRequest()->get("id");
        $formId = $this->getRequest()->get("formId");
        $entity = $this->getDoctrine()->getRepository("VerificacionBundle:Automotor")->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Infraccion entity.');
        }

        $editForm = $this->createForm(new AutomotorType(), $entity);


        return $this->render('InfraccionBundle:Automotor:automotor_edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'formId' => $formId
        ));

    }

    public function  dominioAjaxSaveAction(Request $request, $id){
        //
        $em = $this->getDoctrine()->getManager();
        $log = $this->get("logger");

        $entity = $em->getRepository('VerificacionBundle:Automotor')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('No encuentra la entidad.');
        }

        $editForm = $this->createForm(new AutomotorType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->render('InfraccionBundle:Infraccion:_dominio_ver.html.twig', array(
                'entity'      => $entity
            ));

        }




        return $this->render('InfraccionBundle:Automotor:automotor_edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));

    }

}