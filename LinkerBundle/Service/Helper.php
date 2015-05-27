<?php

namespace LinkerBundle\Service;

use LinkerBundle\Entity\Link;
use Symfony\Component\HttpFoundation\Response;

class Helper
{
	protected $doctrine;

	public function Helper($name)
	{
		return $name;
	}

	public function randGenerator($length = 4)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	return $randomString;
	}

	public function unique($string)
	{
		$dm=$this->doctrine->getManager();
		$flag = true;
        $sprawdzanyLink=$dm->getRepository('LinkerBundle:Link')->findByShortLink($string);
        if($sprawdzanyLink)
        {
        	$flag = false;
        }
        return $flag;
	}

	public function setDoctrine($doctrine)
	{

		$this->doctrine = $doctrine;
	}



}