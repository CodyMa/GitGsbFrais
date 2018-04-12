<?php

namespace GsbFrais\ProfilBundle\Controller;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\TextType;
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

        $form = $this->createFormBuilder($ficheFrais)
            ->add('prdEngag', DateTimeType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('repasMidi', TextType::class, array('label'=> 'Mot de passe' ,'attr' => array('class'=> 'form-control')))
            ->add('nuitees', TextType::class, array('label'=> 'Mot de passe' ,'attr' => array('class'=> 'form-control')))
            ->add('etape', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('km', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('dateHf', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('libelle', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('montant', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('nbJustif', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('montantTot', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('', ChoiceType::class, array('label'=> 'Statut' , 'attr' => array('class'=> 'custom-select'),
                'choices'  => array(
                    'Statut :' => array('Visiteur' => 'visiteur', 'Comptable' => 'comptable', ),
                )))
            ->add('save', SubmitType::class, array('label'=> 'Se connecter' ,'attr' => array('class'=> 'btn btn-primary',  'id' => 'btnSave')))
            ->getForm();


        return $this->render(
            'profil/saisieFrais.html.twig' ,
            array(
                'form' => $form->createView()
            )
        );
    }
}
