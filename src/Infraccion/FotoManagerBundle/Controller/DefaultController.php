<?php

namespace Infraccion\FotoManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FotoManagerBundle:Default:index.html.twig');
    }
}
