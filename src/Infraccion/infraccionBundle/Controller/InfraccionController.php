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

use Infraccion\infraccionBundle\Form\FiltroParmType;
use Symfony\Component\HttpFoundation\Response;

use Infraccion\infraccionBundle\lib\Bitmap;

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

        if(!$data){
            $session->getFlashBag()->add('error', 'Primero debe establecer los parametraos de trabajo.');
            return $this->redirect($this->generateUrl('infraccion_filterparm'));
        }



        $filtro = new Infraccion();
        $filtro->setMunicipio($data->getMunicipio());
        $filtro->setUbicacion($data->getUbicacion());
        $filtro->setTipoInfraccion($this->getDoctrine()->getRepository("InfraccionBundle:TipoInfraccion")->find($data->getTipoInfraccion()->getid()));



        list($filterForm, $queryBuilder) = $this->filter();
        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

/*
        foreach($entities as $entity){
            try{
                $entity->setAutomotor($this->getDoctrine()->getRepository("VerificacionBundle:Automotor")->findOneByDominio($entity->getDominio()) );
            }catch(\Exception $e){
                $entity->setAutomotor(new Automotor());
            }
        }
*/
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

        if($data)
            $queryBuilder = $em->getRepository('InfraccionBundle:Infraccion')->getInfraccionQuery($data->getMunicipio()->getId(),$data->getUbicacion()->getId(),$data->getTipoInfraccion()->getId(),$data->getFecha());
        else{
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
        $routeGenerator = function($page) use ($me)
        {
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
        $entity  = new Infraccion();
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
            'form'   => $form->createView(),
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
        $breadcrumbs->addItem("Nuevo" );
        $entity = new Infraccion();
        $form   = $this->createForm(new InfraccionType(), $entity);

        return $this->render('InfraccionBundle:Infraccion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
        $breadcrumbs->addItem("Ver" );

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Infraccion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfraccionBundle:Infraccion:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Infraccion entity.
     *
     */
    public function editAction($id)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Infraccion", $this->get("router")->generate("infraccion"));
        $breadcrumbs->addItem("Editar" );

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Infraccion entity.');
        }

        $editForm = $this->createForm(new InfraccionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfraccionBundle:Infraccion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Infraccion entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Infraccion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new InfraccionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');

            return $this->redirect($this->generateUrl('infraccion_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Infraccion", $this->get("router")->generate("infraccion"));
        $breadcrumbs->addItem("Editar" );

        return $this->render('InfraccionBundle:Infraccion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
            ->getForm()
            ;
    }


    public function filterParmAction(){
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $entity  = new Infraccion();
        $entity->setFecha(new \DateTime("now"));

        if ($request->getMethod() == 'POST') {
            $muni  = $request->get("infraccion_infraccionbundle_FiltroParmtype")['municipio'];

            $form = $this->createForm(
                new FiltroParmType(
                    $em->getRepository("InfraccionBundle:Ubicacion"),
                    $muni),
                $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $session->getFlashBag()->add('success', 'Filtro creado con exito');
                $filterData = $form->getData();
                $session->set('InfraccionFilterParm', $filterData);
                return $this->redirect($this->generateUrl('infraccion'));
            }

        }else{

            $data = $session->get('InfraccionFilterParm');
            if($data){
                $entity->setMunicipio( $em->getRepository("InfraccionBundle:Municipio")->find($data->getMunicipio()->getid()));
                $entity->setUbicacion( $em->getRepository("InfraccionBundle:Ubicacion")->find($data->getUbicacion()->getid()));
                $entity->setTipoInfraccion(  $em->getRepository("InfraccionBundle:TipoInfraccion")->find($data->getTipoInfraccion()->getid()));
                $entity->setFecha($data->getFecha());
            }

            $form = $this->createForm(new FiltroParmType($em->getRepository("InfraccionBundle:Ubicacion")), $entity);

        }


        return $this->render('InfraccionBundle:Infraccion:filterParm.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
        $opciones="";
        foreach($tablas as $tabla){
            $opciones = $opciones."<option value='{$tabla->getid()}'>".$tabla."</option>";
        }

        $response->setContent($opciones);
        return $response;
    }

    public function cambiarFotoAction(Request $request, $id){

        $foto= $request->files->get('foto');
        $nroFoto = $request->get("nrofoto");

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('InfraccionBundle:Infraccion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encuentrta la entidad Municipio.');
        }

      //  ld($foto);
      //  ld($foto->getClientOriginalExtension());




        $filename = sprintf(
                "%02u%02u%02u% 6s%s%d",
                $entity->getMunicipio()->getCodigo(),
                $entity->getUbicacion()->getCodigo(),
                $entity->getTipoInfraccion()->getCodigo(),
                $entity->getDominio(),
                $entity->getFecha()->format('YmdHis'),
                $nroFoto
        );

        $dir = $this->container->getParameter("infraccion.infracciones.dir");
        $dir .= $entity->getFecha()->format('Ym');

        if($foto->getClientOriginalExtension() == 'bmp'){
            //$filename .= ".jpg";
            //$this->ConvertBMP2GD($foto->getPathname(), $dir.'/'.$filename);


            $foto->move($dir,$filename.".bmp");

            $convert = new Bitmap($dir);
            $convert->convertJpg($dir.'/'.$filename.".bmp",$dir.'/'.$filename.".jpg");
            $filename.=".jpg";

        }else{
            $filename .= ".".$foto->getClientOriginalExtension();
            $foto->move($dir, $filename );
        }


        if($nroFoto == 1 ){
            $entity->setFoto1($filename);
        }

        if($nroFoto == 2 ){
            $entity->setFoto2($filename);
        }

        if($nroFoto == 3 ){
            $entity->setFoto3($filename);
        }

        $em->persist($entity);
        $em->flush();

        $response = new Response();

        return $response->setContent($filename);
    }


}
