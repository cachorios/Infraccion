<?php

namespace Infraccion\infraccionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

/**
 *
 */
class ExportarController extends Controller
{
    /**
     * Lists all Municipio entities.
     *
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Municipio", $this->get("router")->generate("municipio"));
        $breadcrumbs->addItem("Exportar dominios" );

        return $this->render('InfraccionBundle:Exportar:index.html.twig', array(

        ));
    }

}
