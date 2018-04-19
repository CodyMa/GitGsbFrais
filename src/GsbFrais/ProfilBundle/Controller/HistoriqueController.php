<?php

namespace GsbFrais\ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class HistoriqueController extends Controller
{
    //AVANT SELECTION
    public function selectionnerAction(Request $request)
    {

        $session = $request->getSession();

        $lesMois = array();
        for ($i = 1 ; $i <= 12 ; $i++){
            $lesMois[$i] = $i;
        }

        $lesAnnee = array();
        for ($i = date('Y') ; $i >= date('Y') - 3 ; $i--){
            $lesAnnee[$i] = $i;
        }

        $form = $this->createFormBuilder(array('allow_extra_field' => true))
            ->add('month', ChoiceType::class, array('label'=> false , 'attr' => array('class'=> 'custom-select'),
                'choices'  => array(
                    'Mois :' => array($lesMois),
                )))
            ->add('year', ChoiceType::class, array('label'=> false , 'attr' => array('class'=> 'custom-select'),
                'choices'  => array(
                    'Années :' => array($lesAnnee),
                )))
            ->add('search', SubmitType::class, array('label'=> 'Rechercher' ,'attr' => array('class'=> 'btn btn-outline-success',  'id' => 'btnSave')))
            ->getForm();


        $request = Request::createFromGlobals();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Recupération données du form
            $data = $form->getData();

            //id du visiteur
            $idVis = $session->get('id');

            //Récupération doctrine
            $em = $this->getDoctrine()->getManager();

            //Récupération de TOUTES les fiches
            $fraisMois = $em->getRepository('GsbFraisProfilBundle:FicheFrais')->getFicheFrais($idVis, $data['month'], $data['year']);
            dump($fraisMois);

            if (!empty($fraisMois)) {

                //Récupération des valeurs dans un tableau
                //Chaque tableau contenue eux meme dans le tableau avec toutes les fiches frais
                $ficheFraisArray = array();
                foreach ($fraisMois as $unFrais) {
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
                        'form' => $form->createView(),
                    ));

            } else {
                $return = $this->render('profil/historiqueFrais.html.twig',
                    array(
                        'selection' => false,
                        'fiches' => null,
                        'form' => $form->createView(),
                    ));
            }

        }
        else{

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
                        'form' => $form->createView(),
                    ));

            }
            else{
                $return = $this->render('profil/historiqueFrais.html.twig',
                    array(
                        'selection' => false,
                        'fiches' => null,
                        'form' => $form->createView(),
                    ));
            }
        }

        return $return;
    }

    //APRES SELECTION
    public function visuAction(Request $request, $date)
    {

        dump($date);
        $session = $request->getSession();

        //id du visiteur
        $idVis = $session->get('id');

        // Les délimiteurs peuvent être des tirets, points ou slash
        list($year, $month, $day) = explode('-', $date);

        $dateFormDate = mktime(0, 0, 0, $month, $day, $year );
        //DATE
        $month = date("m", $dateFormDate);
        $year = date("Y", $dateFormDate);

        //date de début de la fiche frais
        $dateDebut = "01/".date("m/Y", $dateFormDate);
        //Manipulation de la date pour trouver la date de la fin de la fiche
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

        //Récupération de TOUTES les fiches
        $ficheFrais = $em->getRepository('GsbFraisProfilBundle:FicheFrais')->getFichesFrais($idVis);
        dump($ficheFrais);

        if(!empty($ficheFrais)){

            //Récupération des valeurs dans un tableau
            //Chaque tableau contenue eux meme dans le tableau avec toutes les fiches frais
            $ficheFraisArray = array();
            foreach ($ficheFrais as $unFrais){
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

            //Récupération de la fiche de frais
            //$fraisMois = new FicheFrais();
            $fraisMois = $em->getRepository('GsbFraisProfilBundle:FicheFrais')->getFicheFrais($idVis, $month, $year);
            dump($fraisMois);

            //Tous les frais forfaitisés qui seront les entêtes du tableau
            $fraisForfait = $em->getRepository('GsbFraisProfilBundle:FraisForfait')->getLesFraisForfait();
            dump($fraisForfait);

            //MONTANT TOTAL DE LA FICHE
            $totalFiche = 0;


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

            if (!empty($fraisMois)){
                $return = $this->render('profil/historiqueFrais.html.twig',
                    array(
                        'dateDebut' => $dateDebut,
                        'dateFin' => $dateFin,
                        'dateModif' => $fraisMois[0]->getDateModif(),
                        'nbJustificatif' => $fraisMois[0]->getNbJustificatif(),
                        'etat' => $fraisMois[0]->getIdEtat()->getLibelleEtat(),
                        'lesFraisForfaits' => $fraisForfaitMoisArray,
                        'lesFraisHorsForfaits' => $fraisHorsForfaitMoisArray,
                        'totalFraisForfait' => $totalDesFrais,
                        'totalFraisNonForfait' => $totalDesFraisNonForfait,
                        'totalFiche' => $totalFiche,
                        'totalValide' => $fraisMois[0]->getMontantValide(),
                        'selection' => true,
                        'fiches' => $ficheFraisArray,
                        'form' => null,
                    ));
            }
            else{
                $return = $this->render('profil/historiqueFrais.html.twig',
                    array(
                        'dateDebut' => $dateDebut,
                        'dateFin' => $dateFin,
                        'dateModif' => null,
                        'nbJustificatif' => null,
                        'etat' => "non créée",
                        'lesFraisForfaits' => null,
                        'lesFraisHorsForfaits' => null,
                        'totalFraisForfait' => null,
                        'totalFraisNonForfait' => null,
                        'totalFiche' => 0,
                        'totalValide' => 0,
                        'selection' => true,
                        'fiches' => $ficheFraisArray,
                        'form' => null,
                    ));
            }

        }
        else{
            $return = $this->render('profil/historiqueFrais.html.twig',
                array(
                    'selection' => true,
                    'fiches' => null,
                    'form' => null,
                ));
        }

        return $return;
    }
}
