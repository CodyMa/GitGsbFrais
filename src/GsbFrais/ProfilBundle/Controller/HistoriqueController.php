<?php

namespace GsbFrais\ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class HistoriqueController extends Controller
{
    //AVANT SELECTION
    public function selectionnerAction(Request $request)
    {

        $session = $request->getSession();

        //id du visiteur
        $idVis = $session->get('id');

        //Récupération doctrine
        $em = $this->getDoctrine()->getManager();

        //Récupération de TOUTES les fiches
        $fraisMois = $em->getRepository('GsbFraisProfilBundle:FicheFrais')->getFichesFrais($idVis);
        dump($fraisMois);

        if(!empty($fraisMois)){

            //Récupération des valeurs dans un tableau
            //Chaque tableau contenue eux meme dans le tableau avec toutes les fiches frais
            $ficheFraisArray = array();
            foreach ($fraisMois as $unFrais){
                array_push($ficheFraisArray,
                    array(
                        "id" => $unFrais->getId(),
                        "idVisiteur" => $unFrais->getIdVisiteur(),
                        "date" => $unFrais->getDate(),
                        "idVisiteurDate" => $unFrais->getIdVisiteurDate(),
                        "nbJustificatif" => $unFrais->getNbJustificatif(),
                        "montantValide" => $unFrais->getMontantValide(),
                        "dateModif" => $unFrais->getDateModif(),
                        "libelleEtat" => $unFrais->getIdEtat()->getLibelleEtat(),
                    ));
            }

            $return = $this->render('profil/historiqueFrais.html.twig',
                array(
                    'selection' => false,
                    'fiches' => $ficheFraisArray,
                ));

        }
        else{
            $return = $this->render('profil/historiqueFrais.html.twig',
                array(
                    'selection' => false,
                    'fiches' => null,
                ));
        }

        return $return;
    }

    //APRES SELECTION
    public function visuAction()
    {
        return $this->render('profil/historiqueFrais.html.twig',
            array(
                'selection' => true,
            ));
    }
}
