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
 
	final class Viewer extends StaticFactory
	{
		/**
		 * @var PartViewer
		 */
		static private $view			= null;
				
		/**
		 * @param PartViewer $view
		 * 
		 * @return void
		 */
		public static function set(PartViewer $view)
		{
			self::$view = $view;
			return /*void*/;
		}
		
		
		/**
		 * @return PartViewer
		 */
		public static function get()
		{
			if(
				!self::$view
			)
			{
				$viewResolver = 
					MultiPrefixPhpViewResolver::create()->
						setViewClassName('SimplePhpView')->
						addPrefix(
							PATH_TEMPLATES
							.'common'
							.DIRECTORY_SEPARATOR
							.'widgets'
							.DIRECTORY_SEPARATOR
						)->
						addPrefix(
							PATH_TEMPLATES.'web'
							.DIRECTORY_SEPARATOR
							.'widgets'
							.DIRECTORY_SEPARATOR
						);
						
				$partViewer = new PartViewer($viewResolver, Model::create() );
				self::set($partViewer);
			}
			
			return self::$view;
		}
		
	}