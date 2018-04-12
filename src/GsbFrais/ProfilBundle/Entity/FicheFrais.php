<?php

namespace GsbFrais\ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FicheFrais
 *
 * @ORM\Table(name="fiche_frais")
 * @ORM\Entity(repositoryClass="GsbFrais\ProfilBundle\Repository\FicheFraisRepository")
 */
class FicheFrais
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
     * @ORM\ManyToOne(targetEntity="GsbFrais\ConnexionBundle\Entity\Visiteur")
     * @ORM\JoinColumn(name="visiteur_id", referencedColumnName="id")
     */
    private $idVisiteur;

    /**
     * @var date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="idVisiteurDate", type="string", nullable=false, length=100, unique=true)
     */
    private $idVisiteurDate;

    /**
     * @var int
     *
     * @ORM\Column(name="nbJustificatif", type="integer")
     */
    private $nbJustificatif;

    /**
     * @var string
     *
     * @ORM\Column(name="montantValide", type="decimal", precision=10, scale=2)
     */
    private $montantValide;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="date", nullable=true)
     */
    private $dateModif;

    /**
     * @ORM\ManyToOne(targetEntity="GsbFrais\ProfilBundle\Entity\Etat")
     * @ORM\JoinColumn(name="etat_id", referencedColumnName="id")
     */
    private $idEtat;


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
     * Set nbJustificatif
     *
     * @param integer $nbJustificatif
     *
     * @return FicheFrais
     */
    public function setNbJustificatif($nbJustificatif)
    {
        $this->nbJustificatif = $nbJustificatif;

        return $this;
    }

    /**
     * Get nbJustificatif
     *
     * @return int
     */
    public function getNbJustificatif()
    {
        return $this->nbJustificatif;
    }

    /**
     * Set montantValide
     *
     * @param string $montantValide
     *
     * @return FicheFrais
     */
    public function setMontantValide($montantValide)
    {
        $this->montantValide = $montantValide;

        return $this;
    }

    /**
     * Get montantValide
     *
     * @return string
     */
    public function getMontantValide()
    {
        return $this->montantValide;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     *
     * @return FicheFrais
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }
    

    /**
     * Set idVisiteur
     *
     * @param \GsbFrais\ConnexionBundle\Entity\Visiteur $idVisiteur
     *
     * @return FicheFrais
     */
    public function setIdVisiteur(\GsbFrais\ConnexionBundle\Entity\Visiteur $idVisiteur = null)
    {
        $this->idVisiteur = $idVisiteur;

        return $this;
    }

    /**
     * Get idVisiteur
     *
     * @return \GsbFrais\ConnexionBundle\Entity\Visiteur
     */
    public function getIdVisiteur()
    {
        return $this->idVisiteur;
    }

    /**
     * Set idEtat
     *
     * @param \GsbFrais\ProfilBundle\Entity\Etat $idEtat
     *
     * @return FicheFrais
     */
    public function setIdEtat(\GsbFrais\ProfilBundle\Entity\Etat $idEtat = null)
    {
        $this->idEtat = $idEtat;

        return $this;
    }

    /**
     * Get idEtat
     *
     * @return \GsbFrais\ProfilBundle\Entity\Etat
     */
    public function getIdEtat()
    {
        return $this->idEtat;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return FicheFrais
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set idVisiteurDate
     *
     * @param string $idVisiteurDate
     *
     * @return FicheFrais
     */
    public function setIdVisiteurDate($idVisiteurDate)
    {
        $this->idVisiteurDate = $idVisiteurDate;

        return $this;
    }

    /**
     * Get idVisiteurDate
     *
     * @return string
     */
    public function getIdVisiteurDate()
    {
        return $this->idVisiteurDate;
    }
}
