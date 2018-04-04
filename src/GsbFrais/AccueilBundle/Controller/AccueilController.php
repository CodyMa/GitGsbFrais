<?php

namespace GsbFrais\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AccueilController extends Controller
{
    public function indexAction()
    {
        return $this->render('accueil/accueil.html.twig');
    }
}
