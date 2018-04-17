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

        //Si la fiche de frais n'estpas vide
        if(!empty($fraisMois)){

            foreach ($fraisMois as $unFrais){
                $idFiche = $unFrais->getId();
                //dump($idFiche);

                //Les frais forfaitisés du mois ajouté par le visiteur pour remplir le tableau
                $fraisForfaitMois = $em->getRepository('GsbFraisProfilBundle:LigneFraisForfait')->getFraisForfaitMois( $idFiche );
                dump($fraisForfaitMois);

                //Les frais non forfaitisés du mois ajouté par le visiteur pour remplir le tableau
                $fraisHorsForfaitMois = $em->getRepository('GsbFraisProfilBundle:LigneFraisHorsForfait')->getFraisHorsForfaitMois( $idFiche );
                dump($fraisHorsForfaitMois);

                if (!empty($fraisForfaitMois) && !empty($fraisHorsForfaitMois)){
                    $return = $this->render('profil/ficheMois.html.twig',
                        array(
                            'dateDebut' => $dateDebut,
                            'dateFin' => $dateFin,
                            'ficheFrais' => $fraisMois,
                            'lesTitresFraisForfait' => $fraisForfait,
                            'lesFraisForfaits' => $fraisForfaitMois,
                            'lesFraisHorsForfaits' => $fraisHorsForfaitMois,
                        ));
                }
                elseif (empty($fraisForfaitMois)){
                    $return = $this->render('profil/ficheMois.html.twig',
                        array(
                            'dateDebut' => $dateDebut,
                            'dateFin' => $dateFin,
                            'ficheFrais' => $fraisMois,
                            'lesTitresFraisForfait' => $fraisForfait,
                            'lesFraisForfaits' => null,
                            'lesFraisHorsForfaits' => $fraisHorsForfaitMois,
                        ));
                }
                elseif (empty($fraisHorsForfaitMois)){
                    $return = $this->render('profil/ficheMois.html.twig',
                        array(
                            'dateDebut' => $dateDebut,
                            'dateFin' => $dateFin,
                            'ficheFrais' => $fraisMois,
                            'lesTitresFraisForfait' => $fraisForfait,
                            'lesFraisForfaits' => $fraisForfaitMois,
                            'lesFraisHorsForfaits' => null,
                        ));
                }
                else{
                    $return = $this->render('profil/ficheMois.html.twig',
                        array(
                            'dateDebut' => $dateDebut,
                            'dateFin' => $dateFin,
                            'ficheFrais' => $fraisMois,
                            'lesTitresFraisForfait' => $fraisForfait,
                            'lesFraisForfaits' => null,
                            'lesFraisHorsForfaits' => null,
                        ));
                }
            }

        }
        else{
            $return = $this->render('profil/ficheMois.html.twig',
                array(
                    'dateDebut' => $dateDebut,
                    'dateFin' => $dateFin,
                    'ficheFrais' => $fraisMois,
                    'lesTitresFraisForfait' => $fraisForfait,
                    'lesFraisForfaits' => null,
                    'lesFraisHorsForfaits' => null,
                ));
        }

        return $return;
    }
}
