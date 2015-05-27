<?php

namespace ImageEditorBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
/**
 * Class Image
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
     * Image path
     *
     * @var string
     *
     * @ORM\Column(type="text", length=255, nullable=false)
     */
    protected $path;
    /**
     * Image file
     *
     * @var File
     *
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "The maxmimum allowed file size is 5MB.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     */
    protected $file;
     /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addDate", type="datetime")
     */
    private $addDate;
    /**
     * Get addDate
     *
     * @return \DateTime 
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * Set addDate
     *
     * @param \DateTime $addDate
     * @return Post
     */
    public function setAddDate()
    {
        $now = new \DateTime();
        $this->addDate=$now;

        return $this;
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
     * Set name
     *
     * @param string $name
     * @return Post
     */
    public function setName($length=5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $name = '';
        for ($i = 0; $i < $length; $i++) {
        $name .= $characters[rand(0, $charactersLength - 1)];
        }
        $this->name = $name;

        return $this;


    }
    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * Set path
     *
     * @param string $path
     * @return Post
     */
    public function setPath($length=6)
    {   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $path = '';
        for ($i = 0; $i < $length; $i++) {
        $path .= $characters[rand(0, $charactersLength - 1)];
        }
        $this->path = $path;

        return $this;
    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }
	 /**
	 * Called before saving the entity
	 * 
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload()
	{   
	    if (null !== $this->file) {
	        // do whatever you want to generate a unique name
	        $filename = sha1(uniqid(mt_rand(), true));
	        $this->path = $filename.'.'.$this->file->guessExtension();
	    }
	}
	/**
	 * Called before entity removal
	 *
	 * @ORM\PreRemove()
	 */
	public function removeUpload()
	{
	    if ($file = $this->getAbsolutePath()) {
	        unlink($file); 
	    }
	}
	/**
	 * Called after entity persistence
	 *
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload()
	{
	    // The file property can be empty if the field is not required
	    if (null === $this->file) {
	        return;
	    }

	    // Use the original file name here but you should
	    // sanitize it at least to avoid any security issues

	    // move takes the target directory and then the
	    // target filename to move to
	    $this->file->move(
	        $this->getUploadRootDir(),
	        $this->path= $this->getName().'.jpg'
	    );

	    // Set the path property to the filename where you've saved the file
	    //$this->path = $this->file->getClientOriginalName();

	    // Clean up the file property as you won't need it anymore
	    $this->file = null;
	}
	public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/obrazki';
    }

}