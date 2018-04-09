<?php

namespace GsbFrais\ProfilBundle\Controller;

use GsbFrais\ProfilBundle\Entity\FicheFrais;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SaisirFraisController extends Controller
{
    public function saisirAction(Request $request)
    {
        $session = $request->getSession();
        //echo $session->get('id');
        $ficheFrais = new FicheFrais();
        /*
        $form = $this->createFormBuilder($ficheFrais)
            ->add('login', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('mdp', PasswordType::class, array('label'=> 'Mot de passe' ,'attr' => array('class'=> 'form-control')))
            ->add('choices', ChoiceType::class, array('label'=> 'Statut' , 'attr' => array('class'=> 'custom-select'),
                'choices'  => array(
                    'Statut :' => array('Visiteur' => 'visiteur', 'Comptable' => 'comptable', ),
                )))
            ->add('save', SubmitType::class, array('label'=> 'Se connecter' ,'attr' => array('class'=> 'btn btn-primary',  'id' => 'btnSave')))
            ->getForm();
        */

        return $this->render(
            'profil/saisieFrais.html.twig'
        );
    }
}
