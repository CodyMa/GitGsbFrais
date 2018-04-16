<?php

namespace GsbFrais\ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FraisDuMoisController extends Controller
{
    /*public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }*/

    public function afficherAction()
    {
        return $this->render('profil/ficheMois.html.twig');
    }
}
