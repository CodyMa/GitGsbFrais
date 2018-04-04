<?php

namespace GsbFrais\ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GsbFraisProfilBundle:Default:index.html.twig');
    }
}
