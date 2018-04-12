<?php

namespace GsbFrais\ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneFraisForfait
 *
 * @ORM\Table(name="ligne_frais_forfait")
 * @ORM\Entity(repositoryClass="GsbFrais\ProfilBundle\Repository\LigneFraisForfaitRepository")
 */
class LigneFraisForfait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GsbFrais\ProfilBundle\Entity\FicheFrais")
     * @ORM\JoinColumn(name="fiche_frais_id", referencedColumnName="id")
     */
    private $idFicheFrais;

    /**
     * @ORM\ManyToOne(targetEntity="GsbFrais\ProfilBundle\Entity\FraisForfait")
     * @ORM\JoinColumn(name="frais_forfait_id", referencedColumnName="id")
     */
    private $idFraisForfait;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idFicheFrais
     *
     * @param integer $idFicheFrais
     *
     * @return LigneFraisForfait
     */
    public function setIdFicheFrais($idFicheFrais)
    {
        $this->idFicheFrais = $idFicheFrais;

        return $this;
    }

    /**
     * Get idFicheFrais
     *
     * @return int
     */
    public function getIdFicheFrais()
    {
        return $this->idFicheFrais;
    }

    /**
     * Set idFraisForfait
     *
     * @param integer $idFraisForfait
     *
     * @return LigneFraisForfait
     */
    public function setIdFraisForfait($idFraisForfait)
    {
        $this->idFraisForfait = $idFraisForfait;

        return $this;
    }

    /**
     * Get idFraisForfait
     *
     * @return int
     */
    public function getIdFraisForfait()
    {
        return $this->idFraisForfait;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return LigneFraisForfait
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
}

