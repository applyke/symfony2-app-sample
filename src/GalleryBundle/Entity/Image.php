<?php

namespace GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="GalleryBundle\Entity\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`image`")
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=1024) */
    protected $path;

    /** @ORM\Column(type="string", length=1024) */
    protected $url;

    /** @ORM\Column(type="integer") */
    protected $width;

    /** @ORM\Column(type="integer") */
    protected $height;
    
    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="album_id")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $album_id;

    /** @ORM\Column(type="datetime") */
    protected $created;


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
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set created
     * @ORM\PrePersist
     * @param \DateTime
     * @return Image
     */
    public function setCreated()
    {
        $this->created = new \DateTime('now');

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set album_id
     *
     * @param \GalleryBundle\Entity\Album $albumId
     * @return Image
     */
    public function setAlbumId(\GalleryBundle\Entity\Album $albumId = null)
    {
        $this->album_id = $albumId;

        return $this;
    }

    /**
     * Get album_id
     *
     * @return \GalleryBundle\Entity\Album
     */
    public function getAlbumId()
    {
        return $this->album_id;
    }
}
