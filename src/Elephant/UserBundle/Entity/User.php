<?php

namespace Elephant\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="github_id", type="string", nullable=true)
     */
    private $githubID;

    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    private $googleID;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
     * Set githubID
     *
     * @param string $githubID
     * @return User
     */
    public function setGithubID($githubID)
    {
        $this->githubID = $githubID;
    
        return $this;
    }

    /**
     * Get githubID
     *
     * @return string 
     */
    public function getGithubID()
    {
        return $this->githubID;
    }

    /**
     * Set googleID
     *
     * @param string $googleID
     * @return User
     */
    public function setGoogleID($googleID)
    {
        $this->googleID = $googleID;

        return $this;
    }

    /**
     * Get googleID
     *
     * @return string
     */
    public function getGoogleID()
    {
        return $this->googleID;
    }
}
