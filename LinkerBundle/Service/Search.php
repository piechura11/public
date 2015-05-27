<?php
namespace LinkerBundle\Service;

use LinkerBundle\Entity\Link;


class Search
{
	public function querySearch($qb, $data)
	{
		if(!empty($data['shortLink']))
		{
			$qb->andWhere(
				$qb->expr()->orX(
					$qb->expr()->like(
						'a.shortLink',
						$qb->expr()->literal('%'.$data['shortLink'].'%')
						)
	
					)
				);
		}

		if(!empty($data['longLink']))
		{
			$qb->andWhere(
				$qb->expr()->orX(
					$qb->expr()->like(
					'a.longLink',
					$qb->expr()->literal('%'.$data['longLink'].'%'))
					)
				);
		}

		if(!empty($data['addDate']))
		{
			$date = $data['addDate'];
			$qb->andWhere(
				$qb->expr()->orX(
					$qb->expr()->like(
						'a.addDate',
						$qb->expr()->literal('%'.$date->format('Y-m-d').'%')

						)
					)
				);
		}

        return $qb;
	}
}