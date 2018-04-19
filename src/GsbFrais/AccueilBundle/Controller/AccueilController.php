<?php

namespace GsbFrais\AccueilBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AccueilController extends Controller
{
    public function indexAction()
    {
        return $this->render('accueil/accueil.html.twig');
    }

    public function deconnexionAction(Request $request)
    {
        $session = $request->getSession();
        // ici ce pour effacer toutes les sessions
        $session->clear();
        return $this->render('accueil/accueil.html.twig');
    }
}
