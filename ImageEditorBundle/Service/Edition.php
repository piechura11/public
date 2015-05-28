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
    public function resizeAction($prefix, $x1, $y1, $x2, $y2)
    {
    $source = imagecreatefromjpeg($prefix);
    list($width, $height) = getimagesize($prefix);
    $newWidth = $x2 - $x1;
    $newHeight = $y2 - $y1;
	$mini = imagecreatetruecolor($newWidth, $newHeight);
	imagecopyresized($mini,    // uchwyt obrazka wynikowego
	$source,                      // uchwyt obrazka źródłowego 
	$x1,                         // współrzędna x punktu od którego zaczynamy nanoszenie
	$y1,                         // współrzędna y punktu od którego zaczynamy nanoszenie
	$x2,                         // współrzędna x punktu od którego zaczynamy kopiowanie
	$y2,                         // współrzędna y punktu od którego zaczynamy kopiowanie
	$newWidth,                    // szerokość skopiowanego obrazka na obrazku wynikowym
	$newHeight,                   // wysokość skopiowanego obrazka na obrazku wynikowym
	$width,             // szerokość obszaru kopiowanego z obrazka źródłowego
	$height);            // wysokość obszaru kopiowanego z obrazka źródłowego

	imagejpeg($mini, null, 100);
	return true;

    }

}