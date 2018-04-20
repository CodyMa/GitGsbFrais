<?php

namespace GsbFrais\ConnexionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class GestionMdpController extends Controller
{

    public function editerMdpAction(Request $request)
    {

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
            ->add('mdpOld', PasswordType::class, array(
                'label' => 'Mot de passe actuel',
                'attr' => array('class' => 'form-control',)
            ))
            ->add('newMdp', PasswordType::class, array(
                'label' => 'Nouveau mot de passe',
                'attr' => array('class' => 'form-control',)
            ))
            ->add('newMdpBis', PasswordType::class, array(
                'label' => 'Répétez le nouveau mot de passe',
                'attr' => array('class' => 'form-control',)
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

            $mdpOld = $data['mdpOld'];
            $newMdp = $data['newMdp'];
            $newMdpBis = $data['newMdpBis'];

            //SI L'ANCIEN MDP EST MAL RENTRE AVERTISSEMENT
            if($mdpOld != $visiteur[0]->getMdp()) {
                $return = $this->render('profil/gestionMdp.html.twig',
                    array('form' => $form->createView(),
                        'oldMdpInvalid' => true,
                        'newMdpDontMatch' => false,
                ));
            }
            //SI LES NEW MDP SONT DIFFERENTS AVERTISSEMENT
            elseif ($newMdp != $newMdpBis){
                $return = $this->render('profil/gestionMdp.html.twig',
                    array('form' => $form->createView(),
                        'oldMdpInvalid' => false,
                        'newMdpDontMatch' => true,
                    ));
            }
            //SINON ENREGISTREMENT MDP ET REDIRECTION
            else{
                //Modifier le visiteur et enregistrer dans la bdd
                $visiteur[0]->setMdp($newMdp);

                //MISE A JOUR BDD
                $em->flush();

                //RETOUR A L'ACCUEIL POUR MESSAGE
                $return = $this->redirectToRoute('gsb_profil_homepage_modif_mdp');

            }

        }
        else{
            $return = $this->render('profil/gestionMdp.html.twig',
                array('form' => $form->createView(),
                    'oldMdpInvalid' => false,
                    'newMdpDontMatch' => false,
                ));
        }


        return $return;
    }


}
