<?php

namespace GsbFrais\ProfilBundle\Entity;

use ArrayAccess;
use Doctrine\ORM\Mapping as ORM;

/**
 * FicheFrais
 *
 * @ORM\Table(name="fiche_frais")
 * @ORM\Entity(repositoryClass="GsbFrais\ProfilBundle\Repository\FicheFraisRepository")
 */
class FicheFrais implements ArrayAccess
{

    private $container = array();
    public function __construct() {
        $this->container = array(
            "id"    =>  $this->getId(),
            "idVisiteur"  => $this->getIdVisiteur(),
            "date" => $this->getDate(),
            "idVisiteurDate" => $this->getIdVisiteurDate(),
            "nbJustificatif" => $this->getNbJustificatif(),
            "montantValide" => $this->getMontantValide(),
            "dateModif" => $this->getDateModif(),
            "idEtat" => $this->getIdEtat(),
        );
    }

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
     * @var String
     *
     * @ORM\Column(name="idVisiteurDate", type="string", length=100, unique=true, nullable=false)
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
     * @var \Date
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
     * @param \Date $dateModif
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
     * @return \Date
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
     * @param \Date $date
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
     * @return \Date
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



    
    /**
     * Get a data by key
     *
     * @param string The key data to retrieve
     * @access public
     */
    public function &__get ($key) {
        return $this->data[$key];
    }

    /**
     * Assigns a value to the specified data
     *
     * @param string The data key to assign the value to
     * @param mixed  The value to set
     * @access public
     */
    public function __set($key,$value) {
        $this->container[$key] = $value;
    }

    /**
     * Whether or not an data exists by key
     *
     * @param string An data key to check for
     * @access public
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function __isset ($key) {
        return isset($this->container[$key]);
    }

    /**
     * Unsets an data by key
     *
     * @param string The key to unset
     * @access public
     */
    public function __unset($key) {
        unset($this->container[$key]);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
        return isset($this->container[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
            if (is_null($offset)) {
                $this->container[] = $value;
            } else {
                $this->container[$offset] = $value;
            }
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
        unset($this->container[$offset]);
    }
}
