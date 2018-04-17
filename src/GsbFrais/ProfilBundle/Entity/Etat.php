<?php

namespace GsbFrais\ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etat
 *
 * @ORM\Table(name="etat")
 * @ORM\Entity(repositoryClass="GsbFrais\ProfilBundle\Repository\EtatRepository")
 */
class Etat
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
     * @var string
     *
     * @ORM\Column(name="libelleEtat", type="string", length=30, unique=true)
     */
    private $libelleEtat;


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
     * Set libelleEtat
     *
     * @param string $libelleEtat
     *
     * @return Etat
     */
    public function setLibelleEtat($libelleEtat)
    {
        $this->libelleEtat = $libelleEtat;

        return $this;
    }

    /**
     * Get libelleEtat
     *
     * @return string
     */
    public function getLibelleEtat()
    {
        return $this->libelleEtat;
    }
}

