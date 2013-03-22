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

/*
 * This file neede only for meta generation and testing!!!
 */

	// paths
	define('PATH_BASE', dirname(__FILE__).'/../../');

	define('ONPHP_CLASS_CACHE_TYPE', 'noCache');
	
	// onPHP init
	require_once PATH_BASE.'../onphp/global.inc.php';
	
	
	// shared classes
	define(
		'PATH_CLASSES',
		PATH_BASE.'src/classes/'
	);

	// shared templates
	define(
		'PATH_TEMPLATES',
		PATH_BASE.'src/templates/'
	);

	// shared templates
	define(
		'PATH_CONTROLLERS',
		PATH_BASE.'src/controllers/'
	);

	// need in config.paths.inc.php
//	define(
//		'PATH_CLASSES',
//		PATH_CLASSES
//	);

	

	include_once PATH_BASE.'config'.DS.'current'.DS.'config.php.inc.php';
	include_once PATH_BASE.'config'.DS.'include'.DS.'config.paths.inc.php';
	include_once PATH_BASE.'config'.DS.'current'.DS.'config.db.inc.php';
	include_once PATH_BASE.'config'.DS.'current'.DS.'config.debug.inc.php';
	include_once PATH_BASE.'config'.DS.'current'.DS.'config.domain.inc.php';
	include_once PATH_BASE.'config'.DS.'current'.DS.'memcache.inc.php';
	
	define('PATH_NONE_REPOSITORY_DATA',
		DS.'var'.DS.'www'.DS.'non_repository_data'.DS
	);

	if(
		defined('MEMCACHE_SESSION') &&
		MEMCACHE_SESSION == true
	) {
		ini_set('session.save_path', MEMCACHE_HOST.':'.MEMCACHE_PORT);
                Singleton::getInstance('MemcacheSession');
	}

	Cache::setPeer(
		PeclMemcached::create(MEMCACHE_HOST, MEMCACHE_PORT)
	);
//	Cache::setDefaultWorker('NullDaoWorker');
        Cache::setDefaultWorker('CommonDaoWorker'); // кышыровать сколько сказанно
//	Cache::setDefaultWorker('CacheDaoWorker'); // кышыровать навечно
	//Cache::setDefaultWorker('CommonDaoWorkerWithLock'); // хранить скоко сказано
	//Cache::setDefaultWorker('VoodooDaoWorker');

	Cache::setDaoMap(
		array(
//			'AclPermissionDAO' => 'CacheDaoWorker',
		)
	);

	/*
	if (defined('OUTPUT_BUFFERING') && (OUTPUT_BUFFERING == true))
		ob_start(array('ErrorHandler', 'obStart'));
*/