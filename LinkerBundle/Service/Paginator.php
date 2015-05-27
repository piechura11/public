<?php

namespace LinkerBundle\Service;

class Paginator
{
	private $totalPages;
	private $page;
	private $rpp;

	public function __construct($totalCount, $page, $rpp)
	{

		$this->page=$page;
		$this->rpp=$rpp;
		$this->totalPages=$this->setTotalPages($totalCount, $rpp);
	}
	public function setTotalPages($totalCount, $rpp)
	{
	    if ($rpp < 5)
        {
            $rpp = 5; 
        }
        if($rpp > 100)
        {
        	$rpp =100;
        }
        

        $this->totalPages=ceil($totalCount / $rpp);
        return $this->totalPages;	
	}
	public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function getPagesList()
    {

    	//rpp ile rekrdów na stronie
    	//page obecny nr strony
    	//totalPages ile jest po przeliczeniu stron
    	//pageList lista z talicą porunemrowanych stron
        //jeśli pageCount <5 set 
		$pageCount = 5;
        if ($this->totalPages <= $pageCount) //Less than total 5 pages
            {
        	$lista = array();


        	for($i=1; $i<=$this->totalPages; $i++) {
        		$lista[] = $i;

        	}
        	return $lista;
        }

        if($this->page <=3)
            return array(1,2,3,4,5);

        $i = $pageCount;
        $r=array();
        $half = floor($pageCount / 2);
        if ($this->page + $half > $this->totalPages) // Close to end
        {
            while ($i >= 1)
            {
                $r[] = $this->totalPages - $i + 1;
                $i--;
            }
            return $r;
        } else
        {
            while ($i >= 1)
            {
                $r[] = $this->page - $i + $half + 1;
                $i--;
            }
            return $r;
        }
    }




}