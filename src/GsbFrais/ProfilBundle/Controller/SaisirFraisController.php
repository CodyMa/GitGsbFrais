<?php

namespace GsbFrais\ProfilBundle\Controller;

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
            ->add('PeriodeEngagement', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('RepasMidi', TextType::class, array('label'=> 'Mot de passe' ,'attr' => array('class'=> 'form-control')))
            ->add('Nuitees', TextType::class, array('label'=> 'Mot de passe' ,'attr' => array('class'=> 'form-control')))
            ->add('Etape', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('km', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('Date', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('Libelle', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('nombreJustificatif', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
            ->add('MontantTotal', TextType::class, array('label'=> 'Identifiant' ,'attr' => array('class'=> 'form-control')))
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
