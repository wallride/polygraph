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
 

	class StringUtils extends StaticFactory
	{
		
		/**
		 * Генерирует случайную строку
		 * @param integer $length
		 * @return string
		 */
		static public function getRandom($length=5)
		{
			$list = array(
				0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
				'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
				'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 
				'u', 'v', 'w', 'x', 'y', 'z',
			);
			shuffle($list);
			
			$return='';
			
			$maxLength = count($list);
			if( $length > $maxLength )
				$length = $maxLength;
			
			for($i=0; $i<$length; $i++) {
				$return.=array_shift($list);
			}
			
			return $return;
		}
		
		/**
		 * Генерирует случайную строку
		 * @param integer $length
		 * @return string
		 */
		static public function getOnlyRandomNumber($length=5)
		{
			$list = array(
				0, 1, 2, 3, 4, 5, 6, 7, 8, 9
			);
			shuffle($list);
			
			$return='';
			
			$maxLength = count($list);
			if( $length > $maxLength )
				$length = $maxLength;
			
			for($i=0; $i<$length; $i++) {
				$return.=array_shift($list);
			}
			
			return $return;
		}
		
		
		
	}