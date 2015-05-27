<?php
namespace LinkerBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;

class LinkerExtension extends Twig_Extension
{
	public function getFilters()
	{
		return array(
			'longLinkFilter' =>new Twig_Filter_Method($this, 'longLinkFilter'),
			);
	}

	public function longLinkFilter($longLink)
	{

		$ile=strlen($longLink)/3;
		if($ile>30)
		{
			$ile=30;
		}

		$firstPart = substr($longLink, 0, $ile);
		$secendPart = substr($longLink, 2*($ile), $ile);
		
		return $longLink = $firstPart.'(...)'.$secendPart;
	}

	public function getName()
    {
        return 'app_extension';
    }
}