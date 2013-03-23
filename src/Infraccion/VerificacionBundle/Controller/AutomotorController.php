<?php

namespace Infraccion\VerificacionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Infraccion\VerificacionBundle\Entity\Automotor;
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
        list($filterForm, $queryBuilder) = $this->filter();

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

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
        $queryBuilder = $em->getRepository('VerificacionBundle:Automotor')->createQueryBuilder('e');

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
        $breadcrumbs->addItem("Ver" );

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VerificacionBundle:Automotor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Automotor entity.');
        }

        return $this->render('VerificacionBundle:Automotor:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

}
