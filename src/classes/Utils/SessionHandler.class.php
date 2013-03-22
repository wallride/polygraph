<?php
/***************************************************************************
 *   Copyright (C) 2011 by Kutcurua Georgy Tamazievich,                    *
 *   Sergey S. Sergeev                                                     *
 *   email: g.kutcurua@gmail.com, icq: 723737, jabber: soloweb@jabber.ru   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	abstract class SessionHandler extends Singleton
	{
		const DEFAULT_TTL = 3600;

		protected $sessionPath		= null;
		protected $sessionName		= null;

		protected $sessionLifeTime	= null;

		abstract public function open($savePath, $sessionName);
		abstract public function close();
		abstract public function read($id);
		abstract public function write($id, $sessData);
		abstract public function destroy($id);
		abstract public function gc($maxlifetime);

		protected function __construct()
		{
			$this->setTTL();

			session_set_save_handler(
				array(&$this, 'open'),
				array(&$this, 'close'),
				array(&$this, 'read'),
				array(&$this, 'write'),
				array(&$this, 'destroy'),
				array(&$this, 'gc')
			);
		}

		protected function setTTL()
		{
			$ttl = ini_get('session.gc_maxlifetime');

			if (empty($ttl))
				$this->sessionLifeTime = self::DEFAULT_TTL;
			else
				$this->sessionLifeTime = $ttl;

			return $this;
		}
	}
?>