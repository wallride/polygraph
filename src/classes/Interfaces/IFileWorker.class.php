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
 
	interface IFileWorker
	{
		
		/**
		 * @param File $object
		 * @return IFileWorker
		 */
		public function save(File $object);
		
		/**
		 * @param File $object
		 * @return IFileWorker
		 */
		public function drop(File $object);
		
		/**
		 * @param File $object
		 * @return IFileWorker
		 */
		public function add(File $object);
		
		/**
		 * Пингуется ?
		 * @param string $path
		 * @return Boolean
		 */
		public function ping(File $object);
		
		/**
		 * Blob данные
		 * @return mixed
		 */
		public function getBlob(File $object);
		
	}