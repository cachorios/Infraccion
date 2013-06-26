<?php

namespace Infraccion\infraccionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Infraccion\infraccionBundle\lib\Bitmap;

/**
 * Actas controller.
 *
 */
class ActaController extends Controller
{
    /**
     * Generacion de actas.
     *
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Acta");

        return $this->render('InfraccionBundle:Infraccion:acta.html.twig', array(
        ));
    }


}
