<?php
/***************************************************************************
 *   Copyright (C) 2011 by Kutcurua Georgy Tamazievich                     *
 *   email: g.kutcurua@gmail.com, icq: 723737, jabber: soloweb@jabber.ru   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */
 
	class AjaxModelAndView extends ModelAndView
	{
		
		/**
		 * @return AjaxModelAndView
		**/
		public static function create()
		{
			return new self;
		}
		
		public function __construct()
		{
			$this->model = AjaxModel::create();
		}
		
		/**
		 * @return AjaxModel
		 */
		public function getModel()
		{
			return parent::getModel();
		}
		
	}