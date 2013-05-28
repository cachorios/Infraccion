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
use Infraccion\VerificacionBundle\Form\AutomotorType;


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
    /**
     * Creates a new Automotor entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Automotor();
        $form = $this->createForm(new AutomotorType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('automotor_show', array('id' => $entity->getId())));
        }

        return $this->render('VerificacionBundle:Automotor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Automotor entity.
     *
     */
    public function newAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Automotor", $this->get("router")->generate("automotor"));
        $breadcrumbs->addItem("Nuevo" );
        $entity = new Automotor();
        $form   = $this->createForm(new AutomotorType(), $entity);

        return $this->render('VerificacionBundle:Automotor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Automotor entity.
     *
     */
    public function editAction($id)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Automotor", $this->get("router")->generate("automotor"));
        $breadcrumbs->addItem("Editar" );

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VerificacionBundle:Automotor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Automotor entity.');
        }

        $editForm = $this->createForm(new AutomotorType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VerificacionBundle:Automotor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Automotor entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VerificacionBundle:Automotor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Automotor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AutomotorType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');

            return $this->redirect($this->generateUrl('automotor_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Automotor", $this->get("router")->generate("automotor"));
        $breadcrumbs->addItem("Editar" );

        return $this->render('VerificacionBundle:Automotor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Automotor entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VerificacionBundle:Automotor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Automotor entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('automotor'));
    }

    /**
     * Creates a form to delete a Automotor entity by id.
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
}