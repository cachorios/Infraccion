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

    public function importarAction()
    {
//        $importar = new \Infraccion\VerificacionBundle\Entity\Importar();
//        $form = $this->createForm(new \Infraccion\VerificacionBundle\Form\ImportarType(), $importar);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
//            if ($form->isValid()) {

//                $importar->upload();

            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

//            }
        }

        return $this->render('VerificacionBundle:Automotor:importar.html.twig', array( //            'form' => $form->createView()
        ));
    }

    public function phpexcelAction()
    {
        $result1 = '';
        $result1 .= '<h1>inicio!' . \time() . ' </h1>';

        $excelObj = $this->get('xls.load_xls2007')->load($this->container->getParameter('directorio.importa') . "todo1.xlsx");
        $objWorksheet = $excelObj->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();

        $em = $this->getDoctrine()->getManager();

        $result1 .= '<h1>export!' . \time() . ' </h1>';

        $inicio = 2;

        $end = $highestRow;
        $cell = array("dominio" => "A", "marca" => "B", "modelo" => "C", "dni" => "D", "cuit_cuil" => "E", "nombre" => "F", "domicilio" => "G", "codigo_postal" => "H", "provincia" => "I", "localidad" => "J");

        $em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            //... do some work
            for ($row = $inicio; $row <= $end; $row++) {
//                $sql = "INSERT INTO usuarios (nombre, email, telefono) VALUES (′$nombre′, ′$email’, ′$telefono’)";

                $sql = "INSERT INTO automotorimportar(dominio, marca, modelo, dni, cuit_cuil, nombre, domicilio, codigo_postal, provincia, localidad)
                        VALUES ("
                    . '"' . \addslashes($objWorksheet->getCell($cell["dominio"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["marca"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["modelo"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["dni"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["cuit_cuil"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["nombre"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["domicilio"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["codigo_postal"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["provincia"] . $row)->getValue()) . '"' . ','
                    . '"' . \addslashes($objWorksheet->getCell($cell["localidad"] . $row)->getValue()) . '"' . ')';

                $em->getConnection()->executeUpdate($sql);


            }

            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            throw $e;
        }

        $result1 .= '<h1>fin!' . \time() . ' </h1>';

        $result1 .= '<h1>Total de registros:' . ($highestRow - $inicio) . ' </h1>';
        return $this->render('VerificacionBundle:Automotor:importar.html.twig', array(
            'result' => $result1,
        ));

    }

    public function actualizarTablaAction()
    {




        $queryUpdate = "
        Update automotor a  join automotorimportar b on a.dominio = b.dominio
    set a.marca = COALESCE(b.marca,''),
    a.modelo = COALESCE(b.modelo,''),
    a.dni = COALESCE(b.dni,''),
    a.cuit_cuil = COALESCE(b.cuit_cuil,''),
    a.nombre = COALESCE(b.nombre,''),
    a.domicilio = COALESCE(b.domicilio,''),
    a.codigo_postal = COALESCE(b.codigo_postal,''),
    a.provincia = COALESCE(b.provincia,''),
    a.localidad = COALESCE(b.localidad,''),
    a.ultima_actualizacion = SYSDATE()
        ";

        $queryInsert = "
insert into automotor( dominio, marca, modelo, dni, cuit_cuil, nombre, domicilio, codigo_postal, provincia, localidad, ultima_actualizacion )
select dominio, marca, modelo, dni, cuit_cuil, nombre, domicilio, codigo_postal, provincia, localidad, SYSDATE() from automotorimportar
where 0 in( select count(*) from automotor where automotor.dominio = automotor.dominio)
";


    }

}
