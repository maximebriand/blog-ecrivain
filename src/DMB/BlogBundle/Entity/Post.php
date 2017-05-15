<?php

namespace DMB\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="DMB\BlogBundle\Repository\postRepository")
 * @Vich\Uploadable
 */
class Post
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
     * @ORM\ManyToOne(targetEntity="DMB\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_date", type="datetime")
     */
    private $publishedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="chapterNumber", type="integer", nullable=false, unique=true)
     */
    private $chapterNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_activated", type="boolean")
     */
    private $isActivated;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="cover_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_premium", type="boolean")
     */
    private $isPremium;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_notified", type="boolean")
     */
    private $isNotified = "false";



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
     * Set author
     *
     * @param string $author
     *
     * @return post
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     *
     * @return post
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate
     *
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return post
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
     * Set chapterNumber
     *
     * @param integer $chapterNumber
     *
     * @return post
     */
    public function setChapterNumber($chapterNumber)
    {
        $this->chapterNumber = $chapterNumber;

        return $this;
    }

    /**
     * Get chapterNumber
     *
     * @return int
     */
    public function getChapterNumber()
    {
        return $this->chapterNumber;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set coverImage
     *
     * @param string $coverImage
     *
     * @return post
     */
    public function setCoverImage($coverImage)
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * Get coverImage
     *
     * @return string
     */
    public function getCoverImage()
    {
        return $this->coverImage;
    }

    public function getUrl()
    {
        if ($this->isPremium)
        {
            return "/post-premium/" . $this->getId();
        }
        else if (!$this->isActivated)
        {
            return "/post-draft/" . $this->getId();
        }
        else
        {
            return "/post/" . $this->getId();
        }
    }

    /**
     * Set isActivated
     *
     * @param boolean $isActivated
     *
     * @return Post
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * Get isActivated
     *
     * @return boolean
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set isPremium
     *
     * @param boolean $isPremium
     *
     * @return Post
     */
    public function setIsPremium($isPremium)
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    /**
     * Get isPremium
     *
     * @return boolean
     */
    public function getIsPremium()
    {
        return $this->isPremium;
    }


    /*add this method to add the post title in the admin for comment*/
    public function __toString() {
        return $this->getTitle();
    }

    /**
     * Set isNotified
     *
     * @param boolean $isNotified
     *
     * @return Post
     */
    public function setIsNotified($isNotified)
    {
        $this->isNotified = $isNotified;

        return $this;
    }

    /**
     * Get isNotified
     *
     * @return boolean
     */
    public function getIsNotified()
    {
        return $this->isNotified;
    }
}
