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
 
	final class CriteriaToGooglePaginatorConverter
	{		
		/**
		 * @var Criteria
		 */
		private $criteria			= null;
		
		private $offsetLabel		= null;
		
		/**
		 * @param Criteria $criteria
		 * @return CriteriaToGooglePaginatorConverter
		 */
		public static function create(Criteria $criteria)
		{
			return new self($criteria);
		} 
		
		public function __construct(Criteria $criteria)
		{
			$this->criteria = $criteria;
		}
		
		/**
		 * @param string $value
		 * @return CriteriaToGooglePaginatorConverter
		 */
		public function setOffsetLabel($value)
		{
			Assert::isString($value);
			$this->offsetLabel = $value;
			
			return $this;
		}
		
		/**
		 * @return NULL
		 */
		public function getOffsetLabel()
		{
			return $this->offsetLabel;
		}
		
		/**
		 * @return GooglePaginator
		 */
		public function make()
		{
			Assert::isNotNull( $this->getOffsetLabel() );
			
			$criteria = $this->criteria;
			
			$paginator = GooglePaginator::create()->
				setCurrent(
					$criteria->getOffset()
				)->setFactor(
					$criteria->getLimit()
				)->setPrefix(
					$this->getOffsetLabel()
				)->setTotalElementsOnPage(
					$criteria->getLimit()
				)->setTotalElements(
					CriteriaUtils::getTotalCountByCriteria(
						$criteria
					)
				);
				
			return $paginator;
		}
		
	}