<?php

namespace Elephant\AlbumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Elephant\UserBundle\Entity\User as User;
/**
 * Album
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Album
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Elephant\UserBundle\Entity\User", inversedBy="albums")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="album", cascade={"persist", "remove"})
     **/
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="Share", mappedBy="album", cascade={"persist", "remove"})
     **/
    private $shares;

    public function __construct()
    {
        $this->setPhotos(new ArrayCollection());
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
     * Set title
     *
     * @param string $title
     * @return Album
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Album
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
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     * @return Album
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     * @return Album
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param User $author
     * @return Album
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get photos
     *
     * @return Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * add photos
     *
     * @param Photo $photo
     * @return Album
     */
    public function addPhoto(Photo $photo)
    {
        $this->photos->add($photo);

        return $this;
    }

    /**
     * remove photos
     *
     * @param Photo $photo
     * @return Album
     */
    public function removePhoto(Photo $photo)
    {
        $this->photos->remove($photo);

        return $this;
    }

    /**
     * Set photos
     *
     * @param Collection $photos
     * @return Album
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * Get shares
     *
     * @return Collection
     */
    public function getShares()
    {
        return $this->shares;
    }

    /**
     * add shares
     *
     * @param Share $share
     * @return Album
     */
    public function addShare(Share $share)
    {
        $this->shares->add($share);

        return $this;
    }

    /**
     * remove shares
     *
     * @param Share $share
     * @return Album
     */
    public function removeShare(Share $share)
    {
        $this->shares->remove($share);

        return $this;
    }

    /**
     * Set shares
     *
     * @param Collection $shares
     * @return Album
     */
    public function setShares($shares)
    {
        $this->shares = $shares;

        return $this;
    }


    /**
     * toString method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
