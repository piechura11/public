<?php

namespace LinkerBundle\Service\Helper;

class Helper
{
	public function RandGenerator($length = 4)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	return $randomString;
	}
	

}