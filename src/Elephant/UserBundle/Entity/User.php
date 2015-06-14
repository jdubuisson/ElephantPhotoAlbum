<?php

namespace Elephant\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Elephant\AlbumBundle\Entity\Album as Album;

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
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    private $googleID;

    /**
     * @ORM\OneToMany(targetEntity="Elephant\AlbumBundle\Entity\Album", mappedBy="author", cascade={"persist", "remove"})
     **/
    private $albums;

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

    /**
     * Get albums
     *
     * @return Collection
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * add album
     *
     * @param Album $album
     * @return User
     */
    public function addAlbum(Album $album)
    {
        $this->albums->add($album);

        return $this;
    }

    /**
     * Set albums
     *
     * @param Collection $albums
     * @return User
     */
    public function setAlbums($albums)
    {
        $this->albums = $albums;

        return $this;
    }
}
