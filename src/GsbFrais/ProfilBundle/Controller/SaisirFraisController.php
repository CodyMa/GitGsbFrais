<?php

namespace GsbFrais\ProfilBundle\Controller;

use Doctrine\DBAL\Types\DateTimeType;

use GsbFrais\ProfilBundle\Entity\FicheFrais;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SaisirFraisController extends Controller
{
    public function saisirAction(Request $request)
    {
        $session = $request->getSession();
        $id = $session->get('id');

        $em = $this->getDoctrine()->getManager();

        $month = date("m");
        $year = date("Y");

        $ficheFrais = $em->getRepository('GsbFraisProfilBundle:FicheFrais')->getFicheFrais($id,$month,$year);

        dump($ficheFrais);

        $ligneFraisForfait = $em->getRepository('GsbFraisProfilBundle:LigneFraisForfait')->getFraisForfaitMois($ficheFrais);


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
            'id' => 'btnSave')))
        ->add('cancel', ResetType::class, array(
            'label'=> 'Annuler' ,
            'attr' => array('class'=> 'btn btn-outline-danger',
                'id' => 'btnSave')));

        $form = $formBuilder->getForm();





        $request = Request::createFromGlobals();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            foreach ($ligneFraisForfait as $uneLigneFf){

                foreach ($data as $oneData){

                    if($uneLigneFf->getIdFraisForfait()->getId() == key ($data)){

                        $quantite = $oneData;
                        $uneLigneFf->setQuantite($quantite);

                    }

                }

            }

            $em->flush();


        }

        return $this->render(
            'profil/saisieFrais.html.twig',
            array('form' => $form->createView())
        );
    }
}
