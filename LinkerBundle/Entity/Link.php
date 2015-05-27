<?php

namespace LinkerBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Link
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Link
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="longLink", type="text")
     */
    private $longLink;

    /**
     * @var string
     * @ORM\Column(name="shortLink", type="string", length=255, nullable=true)
     */
    private $shortLink;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="modyficator", type="integer")
     */
    private $modyficator;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=128, nullable=true)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addDate", type="datetime" )
     */
    private $addDate;
/*
    public function __construct(){
      $this->addDate = new \DateTime();
    }
  */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set longLink
     *
     * @param string $longLink
     * @return Link
     */
    public function setLongLink($longLink)
    {
        $this->longLink = $longLink;

        return $this;
    }

    /**
     * Get longLink
     *
     * @return string 
     */
    public function getLongLink()
    {
        return $this->longLink;
    }

    /**
     * Set shortLink
     *
     * @param string $shortLink
     * @return Link
     */
    public function setShortLink($shortLink)
    {
        $this->shortLink = $shortLink;

        return $this;
    }

    /**
     * Get shortLink
     *
     * @return string 
     */
    public function getShortLink()
    {
        return $this->shortLink;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Link
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set modyficator
     *
     * @param integer $modyficator
     * @return Link
     */
    public function setModyficator($modyficator)
    {
        $this->modyficator = $modyficator;

        return $this;
    }

    /**
     * Get modyficator
     *
     * @return integer 
     */
    public function getModyficator()
    {
        return $this->modyficator;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Link
     */
    public function setPassword($password)
    {
        $this->password = hash('sha1', $password);

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    

    /**
     * Set addDate
     *
     * @param \DateTime $addDate
     * @return Link
     */
    public function setAddDate()
    {   $now = new \DateTime();
        $this->addDate=$now;

        return $this;
    }

    /**
     * Get addDate
     *
     * @return \DateTime 
     */
    public function getAddDate()
    {
        return $this->addDate;
    }
}
