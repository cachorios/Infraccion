<?php

namespace Infraccion\VerificacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VerificacionBundle:Default:index.html.twig');
    }
}
