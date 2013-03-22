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

	/**
	 * PHP MemCached Session Handler on memcache API.
	 *
	 * It can use that set session.save_path your memcached hostname or
	 * ipaddress and require this file.
	 *
	 * <code>
	 *	ini_set('session.save_path', '<host>:<port>');
	 *	Singleton::getInstance('MemcacheSession');
	 * </code>
	 */
	class MemcacheSession extends SessionHandler
	{
		const DEFAULT_PORT		= 11211;

		protected $isConnected	= false;
		protected $isPersistent	= true;
		protected $db			= null;

		public function open($savePath, $sessionName)
		{
			$this->sessionPath = $savePath;
			$this->sessionName = $sessionName;

			$this->connect();

			return true;
		}

		public function close()
		{
			if ($this->isPersistent === true) {
				return true;
			}

			if ($this->isConnected === true) {
				$this->db->close();
				$this->isConnected = false;
			}

			return true;
		}

		public function read($id)
		{
			$this->connect();

			if ($sessData = $this->db->get($id))
				return $sessData;
			else
				return '';
		}

		public function write($id, $sessData)
		{
			$this->connect();

			$id = trim($id);

			if (empty($id))
				return false;

			return $this->db->set($id, $sessData, 0, $this->sessionLifeTime);
		}

		public function destroy($id)
		{
			$this->connect();

			return $this->db->delete($id);
		}

		public function gc($maxlifetime)
		{
			return true;
		}

		protected function connect()
		{
			if ($this->isConnected === true)
				return $this;

			$params = explode(':', ini_get('session.save_path'));

			$host = $params['0'];

			if (isset($params['1']))
				$port = $params['1'];
			else
				$port = self::DEFAULT_PORT;

			$connection = new Memcache();

			if ($this->isPersistent)
				$connection->pconnect($host, $port);
			else
				$connection->connect($host, $port);

			$this->db = $connection;
			$this->isConnected = true;

			return $this;
		}
	}
?>