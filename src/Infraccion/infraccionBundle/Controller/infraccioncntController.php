<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 9/08/13
 * Time: 16:21
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\infraccionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\CallbackAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Infraccion\infraccionBundle\Entity\Infraccion;
use Infraccion\infraccionBundle\Form\InfraccionFilterType;


class infraccioncntController extends Controller
{
    private $queryBuilder;
    /**
     * Lists all Infraccion entities.
     */
    /**
     * @Route("/infracciongrp")
     *
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Fechas de Infracciones", $this->get("router")->generate("infraccion"));

        $request = $this->getRequest();
        $session = $request->getSession();

        //$data = $session->get('InfraccionFilterParm');

        /*if (!$data) {
            $session->getFlashBag()->add('error', 'Primero debe establecer los parametraos de trabajo.');
            return $this->redirect($this->generateUrl('infraccion_filterparm'));
        }

        */
        $filtro = new Infraccion();
        /*$filtro->setMunicipio($data->getMunicipio());
        $filtro->setUbicacion($data->getUbicacion());
        $filtro->setTipoInfraccion($this->getDoctrine()->getRepository("InfraccionBundle:TipoInfraccion")->find($data->getTipoInfraccion()->getid()));
        $filtro->setFecha($data->getFecha());
        */

        list($filterForm, $this->queryBuilder) = $this->filter();
        list($entities, $pagerHtml) = $this->paginator($this->queryBuilder);


        return $this->render('InfraccionBundle:Infraccioncnt:index.html.twig', array(
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

        $data = $session->get('InfraccioncntFilterParm');

        //---------
        /****
        if ($data)
            $queryBuilder = $em->getRepository('InfraccionBundle:Infraccion')->getInfraccionQuery($data->getMunicipio()->getId(), $data->getUbicacion()->getId(), $data->getTipoInfraccion()->getId(), $data->getFecha());
        else {
            return $this->redirect($this->generateUrl('infraccion_filterparm'));
        }
        */

        $queryBuilder = $em->getRepository('InfraccionBundle:Infraccion')->getInfraccionGroupQuery();


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


    public function getNroRegistro(){
        return 0;
    }

    public function getSlice($offset, $length){
       // $this->queryBuilder->setFirstResult($offset);
       // $this->queryBuilder->setMaxResults($length);


        //ladybug_dump_die( $this->queryBuilder);
        //ladybug_dump_die( $this->queryBuilder->getArrayResult() );
        return $this->queryBuilder->getResult();

    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder)
    {
        // Paginator
        $adapter = new CallbackAdapter(array($this,"getNroRegistro"),array($this,"getSlice"));

        //DoctrineORMAdapter($queryBuilder);
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

        //ladybug_dump_die($pagerHtml);
        return array($entities, $pagerHtml);
    }
}