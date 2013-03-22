<?php
/***************************************************************************
 *   Copyright (C) 2010 by Kutcurua Georgy Tamazievich                     *
 *   email: g.kutcurua@gmail.com, icq: 723737, jabber: soloweb@jabber.ru   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	final class CriteriaUtils extends StaticFactory {

		/**
		 * @param Criteria $criteria
		 * @return Integer
		 */
		public static function getTotalCountByCriteria(Criteria $criteria) {

			$criteria = clone $criteria;
			
			$criteria->dropOrder();
			$criteria->setLimit(null);
			$criteria->setOffset(null);
			
			
			/*
			 * Если у нас присутствует сложная логика в критерии,
			 * то нужно считать по доугому,
			 * так например если у нас группировки в запросе, то count будет недействительным
			 */
			if(
				!$criteria->getProjection()->isEmpty()
			) {
				
				
				$criteria->dropProjectionByType(
					array(
						'PropertyProjection',
						//'GroupByPropertyProjection',
						//'GroupByClassProjection',
						'ClassProjection',
					)
				);
				
				$criteria->addProjection(
					Projection::property('id')
				);
			
				$query = $criteria->toSelectQuery();
				
				$query->dropLimit();
				$query->dropOrder();
				
				
				$masterQuery = new SelectQuery();
				
				$masterQuery->get(
					SQLFunction::create('count', '*'),
					'count'
				);
				
				$db = DBPool::getByDao( $criteria->getDao() );
				
				$masterQuery->from(
					$query,
					'sub'
				);
				
				$queryResult = $db->queryColumn( $masterQuery );
			
		
				$count = $queryResult[0];
				
				return (integer) $count;
				
				
			}
			
			
			
			
			$criteria->addProjection(
				Projection::count('id', 'count')
			);
			
			$count = $criteria->getCustom('count');

			return (integer) $count;

		}
		
		/**
		 * @param Criteria $criteria
		 * @return string
		 */
		public static function encode( Criteria $criteria )
		{			
			return 
				base64_encode( serialize($criteria) );
				
		}
		
		/**
		 * @param string $encoded_criteria
		 * @return Criteria
		 */
		public static function decode( $encoded_criteria )
		{
			
			$criteria = base64_decode($encoded_criteria);
			$criteria = unserialize($criteria);
			
			return 
				$criteria;
				
		}


	}

?>