<?php

namespace GsbFrais\ConnexionBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;



class GestionCompteController extends Controller
{

    public function editerUserAction(Request $request)
    {
        //MODIFIER VISITEUR DANS BDD ET DANS LA SESSION!!!!!



        //RECUPERATION DE L'ID DU VISITEUR DANS LA SESSION
        $session = $request->getSession();
        $id = $session->get('id');

        //RECUPERATION DOCTRINE
        $em = $this->getDoctrine()->getManager();

        //RECUPERATION DU VISITEUR
        $visiteur = $em->getRepository('GsbFraisConnexionBundle:Visiteur')->getVisiteur($id);
        dump($visiteur);


        //CREATION DU FORMULAIRE à MODIFIER
        $form = $this->createFormBuilder(array('allow_extra_field' => true))
            ->add('nom', TextType::class, array(
                'label' => 'Nom',
                'attr' => array('class' => 'form-control',
                    'placeholder' => $visiteur[0]->getNom(),
                    'value' => $visiteur[0]->getNom(),)
            ))
            ->add('prenom', TextType::class, array(
            'label' => 'Prenom',
            'attr' => array('class' => 'form-control',
                'placeholder' => $visiteur[0]->getPrenom(),
                'value' => $visiteur[0]->getPrenom(),)
            ))
            ->add('adresse', TextType::class, array(
                'label' => 'Adresse',
                'attr' => array('class' => 'form-control',
                    'placeholder' => $visiteur[0]->getAdresse(),
                    'value' => $visiteur[0]->getAdresse(),)
            ))
            ->add('cp', TextType::class, array(
                'label' => 'Code postal',
                'attr' => array('class' => 'form-control',
                    'title' => 'Entre 2 et 5 caractères.',
                    'value' => $visiteur[0]->getCp(),
                    'placeholder' => $visiteur[0]->getCp(),)
            ))
            ->add('ville', TextType::class, array(
                'label' => 'Ville',
                'attr' => array('class' => 'form-control',
                    'placeholder' => $visiteur[0]->getVille(),
                    'value' => $visiteur[0]->getVille())
            ))
            ->add('save', SubmitType::class, array(
                'label'=> 'Modifier' ,
                'attr' => array('class'=> 'btn btn-outline-primary',
                    'id' => 'btnSave')))
            ->add('cancel', ResetType::class, array(
                'label'=> 'Annuler' ,
                'attr' => array('class'=> 'btn btn-outline-danger',
                    'id' => 'btnSave')))
            ->getForm();

        //Creation du formulaire
        $request = Request::createFromGlobals();
        $form->handleRequest($request);

        //SI LE FORMULAIRE EST ENVOYE ET QUE LES CHAMPS SONT CONFORMES
        if ($form->isSubmitted() && $form->isValid()) {

            //Récupération des données
            $data = $form->getData();

            $nom = $data['nom'];
            $prenom = $data['prenom'];
            $adresse = $data['adresse'];
            $cp = $data['cp'];
            $ville = $data['ville'];

            //Modifier le visiteur et enregistrer dans la bdd
            $visiteur[0]->setNom($nom);
            $visiteur[0]->setPrenom($prenom);
            $visiteur[0]->setAdresse($adresse);
            $visiteur[0]->setCp($cp);
            $visiteur[0]->setVille($ville);

            //MISE A JOUR BDD
            $em->flush();

            //RECTIFICATION DE LA SESSION
            $session->set('nom', $nom);
            $session->set('prenom', $prenom);

            $return = $this->redirectToRoute('gsb_profil_homepage_modif');
            /*$resp = new Response();
            $resp->setContent('Bien joué');
            return $resp;*/

        }
        else{
            $return = $this->render('profil/gestionCompte.html.twig',
                array('form' => $form->createView(),
                ));
        }


        return $return;
    }

}
