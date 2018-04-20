<?php

namespace GsbFrais\ProfilBundle\Controller;

use Doctrine\DBAL\Types\DateTimeType;

use GsbFrais\ProfilBundle\Entity\FicheFrais;
use GsbFrais\ProfilBundle\Entity\LigneFraisHorsForfait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SaisirFraisController extends Controller
{
    public function saisirAction(Request $request)
    {


        //FORMULAIRE FRAIS FORFAIT

        $session = $request->getSession();
        $id = $session->get('id');

        $em = $this->getDoctrine()->getManager();

        $month = date("m");
        $year = date("Y");

        $ficheFrais = $em->getRepository('GsbFraisProfilBundle:FicheFrais')->getFicheFrais($id,$month,$year);

        dump($ficheFrais);

        $ligneFraisForfait = $em->getRepository('GsbFraisProfilBundle:LigneFraisForfait')->getFraisForfaitMois($ficheFrais[0]->getId());


        $formBuilder = $this->createFormBuilder(array('allow_extra_field' => true));

        foreach( $ligneFraisForfait as $laLigneFf ){
            $idFf = $laLigneFf->getIdFraisForfait()->getId();
            $nomFf = $laLigneFf->getIdFraisForfait()->getLibelle();
            $quantite = $laLigneFf->getQuantite();

            $formBuilder->add($idFf, TextType::class, array(
                'label' => $nomFf,
                'attr' => array('class' => 'form-control',
                    'value' => $quantite,)
            ));
        }
        $formBuilder
        ->add('save', SubmitType::class, array(
        'label'=> 'Modifier' ,
        'attr' => array('class'=> 'btn btn-outline-primary',
            'id' => 'btnSave',
            //'style' => 'width:100%',
            )))
        ->add('cancel', ResetType::class, array(
            'label'=> 'Annuler' ,
            'attr' => array('class'=> 'btn btn-outline-danger',
                'id' => 'btnSave',
                'style' => 'margin-left:1rem',
                )));

        $form = $formBuilder->getForm();





        $request = Request::createFromGlobals();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            foreach ($ligneFraisForfait as $uneLigneFf){

                foreach (array_keys($data) as $oneData){

                    if($uneLigneFf->getIdFraisForfait()->getId() == $oneData){

                        $quantite = $data[$oneData];
                        $uneLigneFf->setQuantite($quantite);

                    }

                }

            }

            $ficheFrais[0]->setDateModif(new \DateTime("now"));


            $em->flush();

            return $this->redirectToRoute('gsb_profil_saisie_frais');


        }



        //FORMULAIRE FRAIS FORFAIT


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


        $lesJours = array();
        for ($i = 1 ; $i <= date("d", $dateManip) ; $i++){
            $lesJours[$i] = $i;
        }


        $formHorsFrais = $this->createFormBuilder(array('allow_extra_field' => true))
            ->add('jour', ChoiceType::class, array('label'=> false , 'attr' => array('class'=> 'custom-select'),
                'choices'  => array(
                    'Jours :' => array($lesJours),
                )))
            ->add('mois', TextType::class, array(
                'label' => false,
                'attr' => array('class' => 'form-control',
                    'value' => $month,
                    'disabled' => true,)
            ))
            ->add('annee', TextType::class, array(
                'label' => false,
                'attr' => array('class' => 'form-control',
                    'value' => $year,
                    'disabled' => true,)
            ))
            ->add('description', TextType::class, array(
                'label' => 'Description',
                'attr' => array('class' => 'form-control',)
            ))
            ->add('montant', TextType::class, array(
                'label' => 'Montant',
                'attr' => array('class' => 'form-control',)
            ))
            ->add('save', SubmitType::class, array(
                'label'=> 'Ajouter' ,
                'attr' => array('class'=> 'btn btn-outline-primary',
                    'id' => 'btnSave',
                    'style' => 'width:100%;margin-top: 2rem;',
                )))
            ->add('cancel', ResetType::class, array(
                'label'=> 'Annuler' ,
                'attr' => array('class'=> 'btn btn-outline-danger',
                    'id' => 'btnSave',
                    'style' => 'width:100%;margin-top: 2rem;',
                )))
            ->getForm();


        $request = Request::createFromGlobals();
        $formHorsFrais->handleRequest($request);


        if ($formHorsFrais->isSubmitted() && $formHorsFrais->isValid()) {

            $data = $formHorsFrais->getData();

            $fraisHorsForfait = new LigneFraisHorsForfait();

            $dateMk = mktime(0, 0, 0, $month, $data['jour'], $year);
            dump($dateMk);
            $dateLigne = new \DateTime(date("Y-m-d", $dateMk));
            $dateModifFiche = new \DateTime("now");
            $libelleLigne = $data['description'];
            $montantLigne = $data['montant'];

            $fraisHorsForfait->setIdFicheFrais($ficheFrais[0]);
            $fraisHorsForfait->setMontant($montantLigne);
            $fraisHorsForfait->setDate($dateLigne);
            $fraisHorsForfait->setLibelle($libelleLigne);

            $ficheFrais[0]->setDateModif($dateModifFiche);

            $em->persist($fraisHorsForfait);
            $em->flush();

            return $this->redirectToRoute('gsb_profil_saisie_frais');


        }




        //REMPLISSAGE VISU FICHE




        $session = $request->getSession();


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
            $return = $this->render('profil/saisieFrais.html.twig',
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
                    'form' => $form->createView(),
                    'form2' => $formHorsFrais->createView(),
                ));
        }
        else{
            $return = $this->render('profil/saisieFrais.html.twig',
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
                    'form' => $form->createView(),
                    'form2' => $formHorsFrais->createView(),
                ));
        }

        return $return;

    }

    public function supprimerAction(Request $request, $idHorsForfait)
    {


        $em = $this->getDoctrine()->getManager();



        //SUPPRESSION DU HORS FORFAIT SELECTIONNE
        $horsForfaitSuppr = $em->getRepository('GsbFraisProfilBundle:LigneFraisHorsForfait')->getLeFraisHorsForfaitMois($idHorsForfait);

        //SUPPR DANS BDD
        $em->remove($horsForfaitSuppr[0]);

        //MISE A JOUR
        $em->flush();

        return $this->redirectToRoute('gsb_profil_saisie_frais');

    }
}
