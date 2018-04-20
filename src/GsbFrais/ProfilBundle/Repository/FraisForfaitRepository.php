<?php

namespace GsbFrais\ProfilBundle\Repository;

/**
 * FraisForfaitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FraisForfaitRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLesFraisForfait(){
        //création de ma requête en indiquant l'alias utilisé l'entité "LigneFraisHorsForfait"
        $maRequete = $this->_em->createQueryBuilder()
            //select * from FraisForfait ffo
            ->select('ffo')
            ->from($this->_entityName, 'ffo')
            //Envoie requete
            ->getQuery()
            //Recup resultat sous forme d'objet
            ->getResult();

        //Retour de la réponse
        return $maRequete;
    }


}
