<?php

namespace GsbFrais\ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SaisirFraisController extends Controller
{
    public function Action(Request $request)
    {
        $session = $request->getSession();
        //echo $session->get('id');



        return $this->render(
            'profil/saisieFrais.html.twig'
        );
    }
}
