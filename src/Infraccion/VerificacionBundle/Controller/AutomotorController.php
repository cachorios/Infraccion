<?php

namespace Infraccion\VerificacionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Infraccion\VerificacionBundle\Adapter\AutoAdapter;

use Pagerfanta\View\TwitterBootstrapView;
use Symfony\Component\HttpFoundation\Response;
use Infraccion\VerificacionBundle\Entity\Automotor;
use Infraccion\VerificacionBundle\Entity\AutomotorImportar;
use Infraccion\VerificacionBundle\Form\AutomotorFilterType;


/**
 * Automotor controller.
 *
 */
class AutomotorController extends Controller
{
    /**
     * Lists all Automotor entities.
     *
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Automotor", $this->get("router")->generate("automotor"));
        list($filterForm, $queryBuilder, $queryCount) = $this->filter();

        list($entities, $pagerHtml) = $this->paginator($queryBuilder, $queryCount);

        return $this->render('VerificacionBundle:Automotor:index.html.twig', array(
            'entities' => $entities,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
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
        $filterForm = $this->createForm(new AutomotorFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('VerificacionBundle:Automotor')->getAutomotoresQuery();
        //createQueryBuilder('e');
        $queryCount = $em->getRepository('VerificacionBundle:Automotor')->countAutomotoresQuery();


        // Reset filter
        if ($request->getMethod() == 'POST' && $request->get('filter_action') == 'reset') {
            $session->remove('AutomotorControllerFilter');
        }

        // Filter action
        if ($request->getMethod() == 'POST' && $request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->bind($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryCount);
                // Save filter to session
                $filterData = $filterForm->getData();

                $session->set('AutomotorControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('AutomotorControllerFilter')) {
                $filterData = $session->get('AutomotorControllerFilter');
                $filterForm = $this->createForm(new AutomotorFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryCount);
            }
        }


        //  ld($queryCount);
        //  ld($queryCount->getQuery()->getSingleScalarResult());


        return array($filterForm, $queryBuilder, $queryCount);
    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder, $queryCount)
    {

        // Paginator
        //$adapter = new DoctrineORMAdapter($queryBuilder, false);

        //$adapter = new AutoAdapter($this->getDoctrine()->getManager(), $this->get('session') ,$queryBuilder,$queryCount, false);
        $adapter = new AutoAdapter($this->get('session'), $queryBuilder, $queryCount->getQuery(), false);

        $pagerfanta = new Pagerfanta($adapter);


        if (!$this->getRequest()->get('page')) {
            $this->get('session')->set("automotor_registros", 0);
        }

        $currentPage = $this->getRequest()->get('page', 1);

        $pagerfanta->setMaxPerPage(15);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function ($page) use ($me) {
            return $me->generateUrl('automotor', array('page' => $page));
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
     * Finds and displays a Automotor entity.
     *
     */
    public function showAction($id)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Automotor", $this->get("router")->generate("automotor"));
        $breadcrumbs->addItem("Ver");

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VerificacionBundle:Automotor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Automotor entity.');
        }

        return $this->render('VerificacionBundle:Automotor:show.html.twig', array(
            'entity' => $entity,
        ));
    }

}