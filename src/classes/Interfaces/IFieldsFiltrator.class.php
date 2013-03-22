<?php
/***************************************************************************
 *		Created by Kutcurua Georgy Tamazievich at 09.11.2010 19:09:47
 *		email: g.kutcurua@gmail.com, icq: 723737, jabber: soloweb@jabber.ru
 ***************************************************************************/
/* $Id$ */
 

	interface IFieldsFiltrator
	{
		public function setFields($list);
		public function setAllFields($list);
		public function getList();
	}