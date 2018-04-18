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

        //date de début de la fiche frais du mois
        $dateDebut = "01/".date("m/Y");
        //Manipulation de la date pour trouver la date de la fin du mois
        if($month == 12){
            $dateManip = mktime(0, 0, 0, 1, 1, $year + 1);
        }
        else{
            $dateManip = mktime(0, 0, 0, $month + 1, 1, $year);
        }
        $dateManip--;
        //date de fin de la fiche frais du mois
        $dateFin = date("d/m/Y", $dateManip);


        //Récupération doctrine
        $em = $this->getDoctrine()->getManager();

        //Récupération de la fiche de frais
        //$fraisMois = new FicheFrais();
        $fraisMois = $em->getRepository('GsbFraisProfilBundle:FicheFrais')->getFicheFrais($id, $month, $year);
        dump($fraisMois);

        //Tous les frais forfaitisés qui seront les entêtes du tableau
        $fraisForfait = $em->getRepository('GsbFraisProfilBundle:FraisForfait')->getLesFraisForfait();
        dump($fraisForfait);

        //MONTANT TOTAL DE LA FICHE
        $totalFiche = 0;
        //MONTANT TOTAL DES FRAIS VALIDES
        $totalValide = 0;

        //Si la fiche de frais n'estpas vide
        if(!empty($fraisMois)){

            foreach ($fraisMois as $unFrais){
                $idFiche = $unFrais->getId();
                //dump($idFiche);

                //Les frais forfaitisés du mois ajouté par le visiteur pour remplir le tableau
                $fraisForfaitMois = $em->getRepository('GsbFraisProfilBundle:LigneFraisForfait')->getFraisForfaitMois( $idFiche );
                dump($fraisForfaitMois);

                $fraisForfaitMoisArray = array();
                $totalDesFrais = 0;

                foreach ($fraisForfaitMois as $unFraisMois){
                    array_push($fraisForfaitMoisArray,
                        array(
                            "id" => $unFraisMois->getId(),
                            "idFraisForfait" => $unFraisMois->getIdFraisForfait()->getId(),
                            "libelleFraisForfait" => $unFraisMois->getIdFraisForfait()->getLibelle(),
                            "montantFraisForfait" => $unFraisMois->getIdFraisForfait()->getMontant(),
                            "quantite" => $unFraisMois->getQuantite(),
                        ));
                    $totalDesFrais = $totalDesFrais + ( $unFraisMois->getIdFraisForfait()->getMontant() * $unFraisMois->getQuantite() );

                }
                dump($fraisForfaitMoisArray);

                //Les frais non forfaitisés du mois ajouté par le visiteur pour remplir le tableau
                $fraisHorsForfaitMois = $em->getRepository('GsbFraisProfilBundle:LigneFraisHorsForfait')->getFraisHorsForfaitMois( $idFiche );
                dump($fraisHorsForfaitMois);

                $fraisHorsForfaitMoisArray = array();
                $totalDesFraisNonForfait = 0;

                foreach ($fraisHorsForfaitMois as $unFraisHorsForfait){
                    array_push($fraisHorsForfaitMoisArray,
                        array(
                           "id" => $unFraisHorsForfait->getId(),
                           "montant" => $unFraisHorsForfait->getMontant(),
                           "date" => $unFraisHorsForfait->getDate(),
                           "libelle" => $unFraisHorsForfait->getLibelle(),
                        ));

                    $totalDesFraisNonForfait = $totalDesFraisNonForfait + $unFraisHorsForfait->getMontant();
                }

            }

            $totalFiche = $totalDesFraisNonForfait + $totalDesFrais;

        }


        return $this->render('profil/ficheMois.html.twig',
            array(
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'dateModif' => $fraisMois[0]->getDateModif(),
                'lesFraisForfaits' => $fraisForfaitMoisArray,
                'lesFraisHorsForfaits' => $fraisHorsForfaitMoisArray,
                'totalFraisForfait' => $totalDesFrais,
                'totalFraisNonForfait' => $totalDesFraisNonForfait,
                'totalFiche' => $totalFiche,
                'totalValide' => $totalValide,
            ));
    }
}
