<?php
namespace ImageEditorBundle\Service;


class Edition
{
	protected $prefix;
	protected $uploads;
	protected $brigness;

	public function Edition($name)
	{
		return $name;
	}

	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}
	public function setUploads($uploads)
	{
		$this->uploads = $uploads;
	}

	public function brignessAction($prefix, $uploads, $brigness=false)
    {
    //rozjaśnienie 
    $image=imagecreatefromjpeg($prefix);
    imagefilter($image, IMG_FILTER_BRIGHTNESS, $brigness);
    imagejpeg($image, $uploads, 100);
    return true;   
    }

    public function contrastAction($prefix, $uploads, $contrast)
    {
    //contrast 
    $image=imagecreatefromjpeg($prefix);
    imagefilter($image, IMG_FILTER_CONTRAST, $contrast);
    imagejpeg($image, $uploads, 100);
    return true;
    }

    public function grayAction($prefix, $uploads, $gray=false)
    {
    //szary
    if($gray == true){
    $image=imagecreatefromjpeg($prefix);
    imagefilter($image, IMG_FILTER_GRAYSCALE);
    imagejpeg($image, $uploads, 100);
    }
    return true;
    }

    public function negateAction($prefix, $uploads, $negate=false)
    {
    //negatyw
    if($negate == true){
    $image=imagecreatefromjpeg($prefix);
    imagefilter($image, IMG_FILTER_NEGATE);
    imagejpeg($image, $uploads, 100);
    }
    return true;
    }

    public function colorizeAction($prefix, $uploads, $colorize)
    {
    $image=imagecreatefromjpeg($prefix);
    imagefilter($image, IMG_FILTER_COLORIZE, $colorize[0], $colorize[1], $colorize[2]);
    imagejpeg($image, $uploads, 100);
    return true;
    }

    public function edgeAction($prefix, $uploads, $edge=false)
    {
    if($edge==true){
    $image=imagecreatefromjpeg($prefix);
    imagefilter($image, IMG_FILTER_EDGEDETECT, $edge);
    imagejpeg($image, $uploads, 100);
    }
    return true;
    }

}