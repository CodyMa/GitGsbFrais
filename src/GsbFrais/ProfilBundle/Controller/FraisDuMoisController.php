<?php

namespace GsbFrais\ProfilBundle\Controller;

use GsbFrais\ProfilBundle\Entity\FicheFrais;
use GsbFrais\ProfilBundle\Repository\FicheFraisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class FraisDuMoisController extends Controller
{
    /*public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }*/

    public function afficherAction(Request $request)
    {

        $session = $request->getSession();

        //id du visiteur
        $id = $session->get('id');

        //DATE
        $month = date("m");
        $year = date("Y");

        //Récupération doctrine
        $em = $this->getDoctrine()->getManager();

        //Récupération de la fiche de frais
        //$fraisMois = new FicheFrais();
        $fraisMois = $em->getRepository('GsbFraisProfilBundle:FicheFrais')->getFicheFrais($id, $month, $year);
        dump($fraisMois);

        //Si la fiche de frais n'estpas vide
        if(!empty($fraisMois)){

            foreach ($fraisMois as $unFrais){
                $idFiche = $unFrais->getId();

                //$typeFraisForfait
                $fraisForfaitMois = $em->getRepository('GsbFraisProfilBundle:LigneFraisForfait');
                $fraisHorsForfaitMois = $em->getRepository('GsbFraisProfilBundle:LigneFraisHorsForfait');

            }

        }


        return $this->render('profil/ficheMois.html.twig');
    }
}
