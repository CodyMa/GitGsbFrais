<?php

namespace GsbFrais\ConnexionBundle\Controller;

use GsbFrais\ConnexionBundle\Entity\Visiteur;
use GsbFrais\ProfilBundle\Entity\FicheFrais;
use GsbFrais\ProfilBundle\Entity\LigneFraisForfait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use GsbFrais\ConnexionBundle\Repository\VisiteurRepository;

class ConnexionController extends Controller
{
    public function indexAction()
    {
        return $this->render('connexion/connexion.html.twig');
    }


    public function array_empty($a)
    {
        foreach($a as $k => $v)
            if(empty($v))
                return false;
        return true;
    }

    public function registerAction(Request $request)
    {
        // creates a task and gives it some dummy data for this example


        $form = $this->createFormBuilder(array('allow_extra_field' => true))
            ->add('login', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('mdp', PasswordType::class, array('label'=> 'Mot de passe' ,'attr' => array('class'=> 'form-control')))
            ->add('choices', ChoiceType::class, array('label'=> 'Statut' , 'attr' => array('class'=> 'custom-select'),
                'choices'  => array(
                    'Statut :' => array('Visiteur' => 'visiteur', 'Comptable' => 'comptable', ),
                )))
            ->add('save', SubmitType::class, array('label'=> 'Se connecter' ,'attr' => array('class'=> 'btn btn-primary',  'id' => 'btnSave')))
            ->getForm();


        $request = Request::createFromGlobals();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $data = $form->getData(); // pour les valeurs saisies

            if(strtolower($data['choices']) == 'visiteur'){
                $visiteur = $em->getRepository('GsbFraisConnexionBundle:Visiteur')->seConnecter($data['login'],$data['mdp']); // utilise la fonction seConnecter du répository
                dump($visiteur); //fais un dump du visiteur

                if(!empty($visiteur)){
                    //$visiteurObj = new Visiteur();
                    $idVis= $visiteur[0]->getId();
                    $month = date("m");
                    $year = date("Y");

                    $ficheFrais = $em->getRepository("GsbFraisProfilBundle:FicheFrais")->getLaFicheFraisExist($idVis,$month,$year);
                    $fraisForfait = $em->getRepository("GsbFraisProfilBundle:FraisForfait")->getLesFraisForfait();

                    dump($ficheFrais);


                    $session = new Session();

                    if($ficheFrais === null){
                        $ficheFraisCreer = new FicheFrais();


                        $etat = $em->getRepository("GsbFraisProfilBundle:Etat")->getEtat(3);
                        $idVis = intval($visiteur[0]->getId()) ;
                        $dateNow = new \DateTime("now");

                        dump($dateNow);



                        $ficheFraisCreer->setIdVisiteur($visiteur[0]);
                        $ficheFraisCreer->setDate($dateNow);
                        $ficheFraisCreer->setIdVisiteurDate($idVis.date("Y-m-d"));
                        $ficheFraisCreer->setNbJustificatif(0);
                        $ficheFraisCreer->setMontantValide(0);
                        $ficheFraisCreer->setDateModif($dateNow);
                        $ficheFraisCreer->setIdEtat($etat[0]);


                        if(!$em->contains($ficheFraisCreer)){

                        foreach ($fraisForfait as $unFraisForfait){

                                $ligneFraisForfait = new LigneFraisForfait();

                                $ligneFraisForfait->setIdFicheFrais($ficheFraisCreer);
                                $ligneFraisForfait->setIdFraisForfait($unFraisForfait);
                                $ligneFraisForfait->setQuantite(0);


                                $em->persist($ligneFraisForfait);

                            }

                            $em->persist($ficheFraisCreer);
                            $em->flush();
                       }
                    }

                    foreach ($visiteur as $unVisiteur){
                        $session->set('id', $unVisiteur->getId());
                        $session->set('nom', $unVisiteur->getNom() );
                        $session->set('prenom', $unVisiteur->getPrenom());
                        dump($session);
                        return $this->redirectToRoute('gsb_profil_homepage');
                    }


                }
                else{
                    return $this->render('connexion/connexion.html.twig', array(
                        'form' => $form->createView(),
                        'erreur' => 'false'
                    ));
                }
                //return $this->redirectToRoute('gsb_frais_accueil');

            }
            else{
                //connexion pour comptable car choices == 'comptable'
            }


        }
        else{
            return $this->render('connexion/connexion.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }


}
