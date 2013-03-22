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

	define('DEFAULT_ENCODING', 'UTF-8');
	mb_internal_encoding(DEFAULT_ENCODING);
	mb_regex_encoding(DEFAULT_ENCODING);
	ini_set('default_charset', DEFAULT_ENCODING);
 
	/*
	 * Begin Common link
	 */
	###############################################################################################################
	define('DB_CONNECTOR' , 'PgSQL');
	define('DB_USER'      , 'application');
	define('DB_PASSWORD'  , 'lmdlmd');
	define('DB_BASE'      , 'widgets');
	define('DB_HOST'      , '127.0.0.1:5432');
	define('DB_BASE_PERSISTANT', false);
	
	
	$dblink = DB::spawn(
		DB_CONNECTOR,
		DB_USER,
		DB_PASSWORD,
		DB_HOST,
		DB_BASE,
		DB_BASE_PERSISTANT
	)->setEncoding(
		DEFAULT_ENCODING
	);
		
	DBPool::me()->setDefault(
		$dblink
	);
	
	DBPool::me()->addLink(
		'dblink',
		$dblink
	);
	
	###############################################################################################################
	/*
	 * End Common link
	 */
	
