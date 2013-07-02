<?php

namespace Infraccion\infraccionBundle\Controller;


use Infraccion\VerificacionBundle\Entity\Automotor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Infraccion\infraccionBundle\Entity\Infraccion;
use Infraccion\infraccionBundle\Form\InfraccionType;
use Infraccion\infraccionBundle\Form\InfraccionFilterType;
use Infraccion\infraccionBundle\Form\CedulaParmType;

use Infraccion\infraccionBundle\Form\FiltroParmType;
use Symfony\Component\HttpFoundation\Response;

use Infraccion\infraccionBundle\lib\Bitmap;
use TCPDF;



/**
 * Infraccion controller.
 *
 */
class InfraccionController extends Controller
{
    /**
     * Lists all Infraccion entities.
     *
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Infraccion", $this->get("router")->generate("infraccion"));

        $request = $this->getRequest();
        $session = $request->getSession();

        $data = $session->get('InfraccionFilterParm');

        if (!$data) {
            $session->getFlashBag()->add('error', 'Primero debe establecer los parametraos de trabajo.');
            return $this->redirect($this->generateUrl('infraccion_filterparm'));
        }


        $filtro = new Infraccion();
        $filtro->setMunicipio($data->getMunicipio());
        $filtro->setUbicacion($data->getUbicacion());
        $filtro->setTipoInfraccion($this->getDoctrine()->getRepository("InfraccionBundle:TipoInfraccion")->find($data->getTipoInfraccion()->getid()));
        $filtro->setFecha($data->getFecha());

        list($filterForm, $queryBuilder) = $this->filter();
        list($entities, $pagerHtml) = $this->paginator($queryBuilder);


        return $this->render('InfraccionBundle:Infraccion:index.html.twig', array(
            'entities' => $entities,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'filtro' => $filtro,
        ));
    }

    /**
     * Create filter form and process filter request.
     *
     */
    protected function filter()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new InfraccionFilterType());
        $em = $this->getDoctrine()->getManager();

        $data = $session->get('InfraccionFilterParm');

        if ($data)
            $queryBuilder = $em->getRepository('InfraccionBundle:Infraccion')->getInfraccionQuery($data->getMunicipio()->getId(), $data->getUbicacion()->getId(), $data->getTipoInfraccion()->getId(), $data->getFecha());
        else {
            return $this->redirect($this->generateUrl('infraccion_filterparm'));
        }


        // Reset filter
        if ($request->getMethod() == 'POST' && $request->get('filter_action') == 'reset') {
            $session->remove('InfraccionControllerFilter');
        }

        // Filter action
        if ($request->getMethod() == 'POST' && $request->get('filter_action') == 'filter') {
            // Bind values from the request

            $filterForm->bind($request);


            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('InfraccionControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('InfraccionControllerFilter')) {
                $filterData = $session->get('InfraccionControllerFilter');
                $filterForm = $this->createForm(new InfraccionFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder)
    {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function ($page) use ($me) {
            return $me->generateUrl('infraccion', array('page' => $page));
        };

        // Paginator - view
        $translator = $this->get('translator');
        $view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => $translator->trans('views.index.pagprev', array(), 'JordiLlonchCrudGeneratorBundle'),
            'next_message' => $translator->trans('views.index.pagnext', array(), 'JordiLlonchCrudGeneratorBundle'),
        ));

        return array($entities, $pagerHtml);
    }

    /**
     * Creates a new Infraccion entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Infraccion();
        $form = $this->createForm(new InfraccionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('infraccion_show', array('id' => $entity->getId())));
        }

        return $this->render('InfraccionBundle:Infraccion:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Infraccion entity.
     *
     */
    public function newAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Infraccion", $this->get("router")->generate("infraccion"));
        $breadcrumbs->addItem("Nuevo");
        $entity = new Infraccion();
        $form = $this->createForm(new InfraccionType(), $entity);

        return $this->render('InfraccionBundle:Infraccion:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Infraccion entity.
     *
     */
    public function showAction($id)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Infraccion", $this->get("router")->generate("infraccion"));
        $breadcrumbs->addItem("Ver");

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Infraccion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfraccionBundle:Infraccion:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Infraccion entity.
     *
     */
    public function editAction($id)
    {
        $idForm = $this->getRequest()->get("idForm");
//        $breadcrumbs = $this->get("white_october_breadcrumbs");
//        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
//        $breadcrumbs->addItem("Infraccion", $this->get("router")->generate("infraccion"));
//        $breadcrumbs->addItem("Editar");

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Infraccion entity.');
        }

        $editForm = $this->createForm(new InfraccionType(), $entity);

        return $this->render('InfraccionBundle:Infraccion:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'idForm' => $idForm,
            'error' => "",
        ));
    }

    /**
     * Edits an existing Infraccion entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $response = new Response();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

        $dominio = $entity->getDominio();
        $fecha = $entity->getFecha();

        $idForm = $this->getRequest()->get("idForm");
        try {
            if (!$entity) {
                throw new \Exception('No se puede editar, Id. inexistente.');
            }

            $editForm = $this->createForm(new InfraccionType(), $entity);
            $editForm->bind($request);

            $dir1 = $this->container->getParameter("infraccion.infracciones.dir") . $fecha->format('Ym');

            if ($editForm->isValid()) {
                $move = 0;

                //Verificar el nuevo dominio exista
                $this->CheckOrCreateDom($dominio, $entity);

                if ($fecha->format("YmdHis") != $entity->getFecha()->format("YmdHis") || $dominio == $entity->getDominio()) {
                    if ($entity->getFotoR1() != null) {
                        $filename = $entity->getFoto1();
                        $entity->setFoto1($this->getFotoName($entity, 1) . ".jpg");
                        $this->movefile($dir1 . '/' . $filename, $dir1 . '/' . $entity->getFoto1());
                    }
                    if ($entity->getFotoR2() != null) {
                        $filename = $entity->getFoto2();
                        $entity->setFoto2($this->getFotoName($entity, 2) . ".jpg");
                        $this->movefile($dir1 . '/' . $filename, $dir1 . '/' . $entity->getFoto2());
                    }
                    if ($entity->getFotoR3() != null) {
                        $filename = $entity->getFoto3();
                        $entity->setFoto3($this->getFotoName($entity, 3) . ".jpg");
                        $this->movefile($dir1 . '/' . $filename, $dir1 . '/' . $entity->getFoto3());
                    }

                    $dir2 = $this->container->getParameter("infraccion.infracciones.dir") . $entity->getFecha()->format('Ym');
                    if ($dir1 != $dir2) {
                        if (!is_dir($dir2)) {
                            mkdir($dir2);
                        }
                        $move = 1;
                        if ($entity->getFotoR1()) {
                            $this->movefile($dir1 . '/' . $entity->getFoto1(), $dir2 . '/' . $entity->getFoto1());
                        }
                        if ($entity->getFotoR2()) {
                            $this->movefile($dir1 . '/' . $entity->getFoto2(), $dir2 . '/' . $entity->getFoto2());
                        }
                        if ($entity->getFotoR2()) {
                            $this->movefile($dir1 . '/' . $entity->getFoto3(), $dir2 . '/' . $entity->getFoto3());
                        }
                    }
                }

                $em->persist($entity);
                $em->flush();

                $ret = array(
                    "ok" => 1,
                    "id" => $entity->getId(),
                    "html" => $this->renderView('InfraccionBundle:Infraccion:_tr.html.twig', array('entity' => $entity)),
                    "move" => $move
                );
                $response->setContent(json_encode($ret));
            } else {
                throw new \Exception('Datos invalidos.');
            }
        } catch (\Exception $e) {
            //$this->get('session')->getFlashBag()->add('error', 'flash.update.error');
            $response->setStatusCode(405);
            $response->setContent($this->renderView('InfraccionBundle:Infraccion:edit.html.twig', array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'idForm' => $idForm,
                'error' => $e->getMessage(),
            )));
        }


        return $response;
    }


    /**
     * Deletes a Infraccion entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Infraccion entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('infraccion'));
    }

    /**
     * Creates a form to delete a Infraccion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }


    public function filterParmAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $entity = new Infraccion();
        $entity->setDominio("AAA000");
        $entity->setFecha(new \DateTime("now"));


        if ($request->getMethod() == 'POST') {
            $muni = $request->get("infraccion_infraccionbundle_FiltroParmtype")['municipio'];

            $form = $this->createForm(
                new FiltroParmType(
                    $em->getRepository("InfraccionBundle:Ubicacion"),
                    $muni),
                $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $filterData = $form->getData();
                $session->set('InfraccionFilterParm', $filterData);
                return $this->redirect($this->generateUrl('infraccion'));
            }

        } else {

            $data = $session->get('InfraccionFilterParm');
            if ($data) {
                $entity->setMunicipio($em->getRepository("InfraccionBundle:Municipio")->find($data->getMunicipio()->getid()));
                $entity->setUbicacion($em->getRepository("InfraccionBundle:Ubicacion")->find($data->getUbicacion()->getid()));
                $entity->setTipoInfraccion($em->getRepository("InfraccionBundle:TipoInfraccion")->find($data->getTipoInfraccion()->getid()));
                $entity->setFecha($data->getFecha());
            }

            $form = $this->createForm(new FiltroParmType($em->getRepository("InfraccionBundle:Ubicacion")), $entity);

        }


        return $this->render('InfraccionBundle:Infraccion:filterParm.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));

    }


    /**
     * Para refrescar el combo de Ubicacion, segun la
     * localidad
     * @return Response
     */
    public function refreshUbicacionesAction()
    {
        $response = new Response();

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $id = $request->get("id");
        $tablas = $em->getRepository("InfraccionBundle:Ubicacion")->getUbicacionByMuni($id)->getQuery()->getResult();
        $opciones = "";
        foreach ($tablas as $tabla) {
            $opciones = $opciones . "<option value='{$tabla->getid()}'>" . $tabla . "</option>";
        }

        $response->setContent($opciones);
        return $response;
    }

    public function cambiarFotoAction(Request $request, $id)
    {

        $foto = $request->files->get('foto');
        $nroFoto = $request->get("nrofoto");

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encuentrta la entidad Municipio.');
        }

        $filename = $this->getFotoName($entity, $nroFoto);

//            sprintf(
//            "%02u%02u%02u% 6s%s%d",
//            $entity->getMunicipio()->getCodigo(),
//            $entity->getUbicacion()->getCodigo(),
//            $entity->getTipoInfraccion()->getCodigo(),
//            $entity->getDominio(),
//            $entity->getFecha()->format('YmdHis'),
//            $nroFoto
//        );

        $dir = $this->container->getParameter("infraccion.infracciones.dir");
        $dir .= $entity->getFecha()->format('Ym');

        if ($foto->getClientOriginalExtension() == 'bmp') {

            $foto->move($dir, $filename . ".bmp");

            $convert = new Bitmap($dir);
            $convert->convertJpg($dir . '/' . $filename . ".bmp", $dir . '/' . $filename . ".jpg");
            $filename .= ".jpg";

        } else {
            $filename .= "." . $foto->getClientOriginalExtension();
            $foto->move($dir, $filename);
        }


        if ($nroFoto == 1) {
            $entity->setFoto1($filename);
        }

        if ($nroFoto == 2) {
            $entity->setFoto2($filename);
        }

        if ($nroFoto == 3) {
            $entity->setFoto3($filename);
        }

        $em->persist($entity);
        $em->flush();

        $response = new Response();

        return $response->setContent($filename);
    }

    private function CheckOrCreateDom($oldDominio, Infraccion $entity)
    {
        if ($oldDominio == $entity->getDominio()) {
            return;
        }

        $dominio = $this->getDoctrine()->getRepository("VerificacionBundle:Automotor")->findOneByDominio($entity->getDominio());
        if (count($dominio) == 0) {
            $dominio = new Automotor();
            $dominio->setDominio($entity->getDominio());
        }
        $entity->setAutomotor($dominio);


    }

    private function getFotoName($entity, $nroFoto)
    {
        $filename = sprintf(
            "%02u%02u%02u% 6s%s%d",
            $entity->getMunicipio()->getCodigo(),
            $entity->getUbicacion()->getCodigo(),
            $entity->getTipoInfraccion()->getCodigo(),
            $entity->getDominio(),
            $entity->getFecha()->format('YmdHis'),
            $nroFoto
        );
        return $filename;
    }

    private function movefile($f1, $f2)
    {
        try {
            if (copy($f1, $f2)) {
                unlink($f1);
            }
        } catch (\Exception $e) {
            //
        }
    }

    public function cambiarEtapaAction(Request $request)
    {

        $id = $request->get("id");
        $ch = true;
        $ret = array("ok" => 0);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository("InfraccionBundle:Infraccion")->find($id);

        if ($entity->getEtapa() == 0) {
            $entity->setEtapa(1);
        } elseif ($entity->getEtapa() == 1) {
            $entity->setEtapa(0);
        } else {
            $ret = array("ok" => 0);
            $ch = false;
        }

        if ($ch) {
            $ret = array(
                "ok" => 1,
                "img" => $this->renderView("InfraccionBundle:infraccion:_etapa.html.twig", array("etapa" => $entity->getEtapa()))
            );
            $em->persist($entity);
            $em->flush();
        }

        $response = new Response();
        $response->setContent(json_encode($ret));

        return $response;

    }

    public function cedulaGenAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        //$em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $accion = $request->get("accion");
            $pdf = null;
            $form = $this->createForm(new CedulaParmType());
            $form->bind($request);

            if ($form->isValid()) {
                $dato = $form->getData();

                if ($accion == 1) {
                    $this->generarCedula($dato['municipio'], $dato['fecha_desde'], $dato['fecha_hasta'], $dato['primer_vencimiento'], $dato['segundo_vencimiento']);
                    $pdf = $this->imprimirCedula($dato['municipio'], $dato['fecha_desde'], $dato['fecha_hasta'], $dato['primer_vencimiento'], $dato['segundo_vencimiento']);
                }
                if ($accion == 2) {
                    $this->generarCedula($dato['municipio'], $dato['fecha_desde'], $dato['fecha_hasta'], $dato['primer_vencimiento'], $dato['segundo_vencimiento']);
                }
                if ($accion == 3) {
                    $pdf = $this->imprimirCedula($dato['municipio'], $dato['fecha_desde'], $dato['fecha_hasta'], $dato['primer_vencimiento'], $dato['segundo_vencimiento']);
                }

                if($pdf){
                    return $this->render('InfraccionBundle:Infraccion:verPdf.html.twig',
                        array(
                            'titulo' =>'Impresion de Cedulas',
                            'pdf' => $pdf)
                    );
                }else{
                    return $this->redirect($this->generateUrl('infraccion'));
                }

            }
        }
        $form = $this->createForm(new CedulaParmType());
        return $this->render('InfraccionBundle:Infraccion:cedulaGen.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function generarCedula($municipio, $desde, $hasta, $vto1, $vto2)
    {
        $em = $this->getDoctrine()->getManager();

        $nro = $municipio->getNumCedula();
        $infracciones = $em->getRepository("InfraccionBundle:Infraccion")->getInfraccionToCedula($municipio->getId(), $desde, $hasta);
        foreach ($infracciones as $infraccion) {
            $infraccion->setEtapa(2);
            $infraccion->setNroInfraccion($nro);
            $nro++;
            $infraccion->setFechaCedula(new \DateTime("now"));
            $infraccion->setVto1($vto1);
            $infraccion->setVto2($vto2);

            $municipio->setNumCedula($nro);

            $em->persist($infraccion);
            $em->persist($municipio);
        }
        $em->flush();

        return;


    }

    private function imprimirCedula($municipio, $desde, $hasta, $vto1, $vto2)
    {
        $em = $this->getDoctrine()->getManager();
        $infracciones = $em->getRepository("InfraccionBundle:Infraccion")->getInfraccionToCedulaPrn($municipio->getId(), $desde, $hasta);

        if ($infracciones) {
            $pdf = $this->getPdf("Infraccion");
            $fecha = new \DateTime("now");
            foreach ($infracciones as $infraccion) {
                $infraccion->setVto1($vto1);
                $infraccion->setVto2($vto2);
                $em->persist($infraccion);
                $pdf = $this->getPdfCedula($pdf, $infraccion, $fecha);
            }

            // reset pointer to the last page
            $pdf->lastPage();
            $nombrePdf = 'pdf/' . "cedula_" . sprintf("%s", $fecha->format('Y-m-d')) . '.pdf';

            $pdf->Output($nombrePdf, 'F');

            return $nombrePdf;
            $em->flush();
        }
        return null;

    }


//    public function verCedulaAction(Request $request)
//    {
//        $id = $request->get("id");
//        $em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository("InfraccionBundle:Infraccion")->find($id);
//        $fecha = new \DateTime('now');
//        $vto1 = new \DateTime('now');
//        $vto1->modify("+10 day");
//        $vto2 = new \DateTime('now');
//        $vto2->modify("+24 day");
//
//        return $this->render('InfraccionBundle:Infraccion:cedula.html.twig',
//            array(
//                'entity' => $entity,
//                'fecha' => $fecha,
//                'vto1' => $vto1,
//                'vto2' => $vto2
//            )
//        );
//
//
//    }

    public function verActaAction(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository("InfraccionBundle:Infraccion")->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Infraccion entity.');
        }
        if ($entity->getEtapa() < 2) {
            throw new \Exception('La etapa no es valida.');
        }

        $fecha = new \DateTime('now');

        $pdf = $this->getPdf("Acta");
        $pdf->SetMargins(12, 0, 10);
        $pdf->AddPage();

        $html = $this->renderView('InfraccionBundle:Infraccion:actapdf.html.twig',
            array(
                'entity' => $entity,
                'fecha' => $fecha
            )
        );

        $pdf->writeHTML($html, true, false, true, false, '');

        // reset pointer to the last page
        $pdf->lastPage();
        $nombrePdf = 'pdf/' . "acta_" . sprintf("%08d", $entity->getNroInfraccion()) . $entity->getAutomotor()->getDominio() . '.pdf';

        $pdf->Output($nombrePdf, 'F');

        return $this->render('InfraccionBundle:Infraccion:verPdf.html.twig',
            array(
                'titulo' => "Acta - ".$nombrePdf,
                'pdf' => $nombrePdf)
        );

    }

    public function verCedulaPdfAction(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("InfraccionBundle:Infraccion")->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Infraccion entity.');
        }
        if ($entity->getEtapa() < 2) {
            throw new \Exception('La etapa no es valida.');
        }

        $fecha = new \DateTime('now');

        $pdf = $this->getPdf("Infraccion");

        $pdf = $this->getPdfCedula($pdf, $entity, $fecha);
        // reset pointer to the last page
        $pdf->lastPage();
        $nombrePdf = 'pdf/' . "cedula_" . sprintf("%08d", $entity->getNroInfraccion()) . '.pdf';

        $pdf->Output($nombrePdf, 'F');

        return $this->render('InfraccionBundle:Infraccion:verPdf.html.twig',
            array(
                'titulo' =>'Cedula - '.$nombrePdf,
                'pdf' => $nombrePdf)
        );
    }

    private function getPdfCedula($pdf, $entity, $fecha)
    {
        $pdf->AddPage();

        $html = $this->renderView('InfraccionBundle:Infraccion:cedulapdf.html.twig',
            array(
                'entity' => $entity,
                'fecha' => $fecha,
            )
        );

        $pdf->writeHTML($html, true, false, true, false, '');
        return $pdf;
    }

    /**
     * getPdf
     * @param $titulo
     * @return TCPDF
     */
    private function getPdf($titulo)
    {
        $dir = $this->container->getParameter("kernel.root_dir");
        require_once($dir . "/../vendor/tecnick.com/tcpdf/config/tcpdf_config.php");

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator("LAR");
        $pdf->SetAuthor('Infraccion by LAR');
        $pdf->SetTitle($titulo);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(12, -5, 10);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 10);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $l = Array();
        // PAGE META DESCRIPTORS --------------------------------------
        $l['a_meta_charset'] = 'UTF-8';
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_language'] = 'es';
        // TRANSLATIONS --------------------------------------
        $l['w_page'] = 'página';
        $pdf->setLanguageArray($l);
        $pdf->SetDisplayMode('fullwidth');
        return $pdf;
    }
}
