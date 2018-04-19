<?php

namespace GsbFrais\ProfilBundle\Repository;


/**
 * FicheFraisRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FicheFraisRepository extends \Doctrine\ORM\EntityRepository
{

    public function getFicheFrais ($idVisiteur, $mois, $annee){


        $queryBuilder = $this->createQueryBuilder('ff')
            //->select('ff') //ff = alias de fiche frais => selectionner tous les champs
            //->from( $this->_entityName, 'ff') //FROM FicheFrais
            ->join('ff.idEtat', 'e')
            ->addSelect('e')
            ->where("ff.idVisiteur = :idVisiteur")//comparaison de l'identifiant du visiteur
            ->andWhere("MONTH(ff.date) = :month")//comparaison mois
            ->andWhere("YEAR(ff.date) = :year")//comparaison annee
            //PASSAGE DES PARAMETRES
            ->setParameter('idVisiteur', $idVisiteur)
            ->setParameter('month', $mois)
            ->setParameter('year', $annee)
            ->getQuery()
            ->getResult();

        return $queryBuilder;
    }

    public function getFichesFrais($idVis){

        $queryBuilder = $this->createQueryBuilder('ff')
            //->select('ff') //ff = alias de fiche frais => selectionner tous les champs
            //->from( $this->_entityName, 'ff') //FROM FicheFrais
            ->join('ff.idEtat', 'e')
            ->addSelect('e')
            ->where("ff.idVisiteur = :idVis")//comparaison de l'identifiant du visiteur
            //PASSAGE DES PARAMETRES
            ->setParameter('idVis', $idVis)
            ->orderBy('ff.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $queryBuilder;
    }


}
