<?php

namespace GsbFrais\ConnexionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GsbFraisConnexionBundle:Default:index.html.twig');
    }
}
