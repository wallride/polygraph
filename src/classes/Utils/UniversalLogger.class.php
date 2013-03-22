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

	/**
	 * Универсальный логгер.
	 * По serviceName создается папка в которую складируются логи.
	 *
	 * @example
	 * 		UniversalLogger::create('example')->
	 * 			write('data to write in file')->
	 * 			write('end other data');
	 *
	 */
	final class UniversalLogger
	{
		const DIR_BASE = 'universalLogs/';

		const LOG_FILE_EXTENSION_ERROR   = '.error';
		const LOG_FILE_EXTENSION_SUCCESS = '.success';

		protected $serviceName				 = null;

		protected $isError					 = null;

		protected $path						 = null;

		protected $fullFileName				 = null;

		protected $prefix					 = '';

		/**
		 * Производилась ли хоть какаянибудь запись ?
		 * @var boolean
		 */
		protected $isWritted					 = false;

		/**
		 * Писать сообщения в файл или в error_log
		 * @var bool
		 */
		protected $fileOutput = true;

		/**
		 * @param String $serviceName
		 * @return UniversalLogger
		 */
		public static function create($serviceName, $prefix='') {
			return new self($serviceName, $prefix);
		}

		public function __construct($serviceName, $prefix='') {
			$this->serviceName = $serviceName;
			$this->prefix = $prefix;

			$this->init();
		}

		protected function init() {
			return;
			$this->path =
				PATH_NONE_REPOSITORY_DATA .
				self::DIR_BASE .
				$this->serviceName . DIRECTORY_SEPARATOR .
				date('Y-m').DIRECTORY_SEPARATOR .
				date('Y-m-d').DIRECTORY_SEPARATOR;

			// результат инициализации папки
			$result = true;
			try {
				umask(0022); // rwxr-xr-x
				if ( !is_dir($this->path) ) {
					if (
						!mkdir(
							$this->path,
							0755,	// rwxr-xr-x
							true	// recursive?
						)
					) {
//						error_log(__METHOD__.': Could not create log dir "'.$this->path.'"');
//						throw new WrongStateException('Could not create log dir "'.$this->path.'"');
						$this->fileOutput = false;
					}
				}
			} catch( Exception $e ) {
//				error_log(__METHOD__.': Could not create log dir "'.$this->path.'"');
//				throw new WrongStateException('Could not create log dir "'.$this->path.'"');
				$this->fileOutput = false;
			}

			/*
			 * Генерируем название файла.
			 */
			$this->fullFileName =
				$this->path .
				$this->prefix .
				date('His');

		}

		/**
		 * @param Boolean $value
		 * @return UniversalLogger
		 */
		public function setError($value) {
			$this->isError = ( $value == true );

			return $this;
		}

		/**
		 * @return Boolean
		 */
		public function isError() {
			return $this->isError;
		}

		/**
		 * @param string $data
		 * @return UniversalLogger
		 */
		public function write($data)
		{

			// Костыльное отключение
			return $this;




			if( $this->fileOutput ) {
				file_put_contents(
					$this->fullFileName
						.(
							(
								$this->isError() &&
								!$this->isWritted
							)
								? self::LOG_FILE_EXTENSION_ERROR
								: self::LOG_FILE_EXTENSION_SUCCESS
						),
					'['.date('Y-M-d H:i:s').'] '. $data . "\n",
					FILE_APPEND
				);
				$this->isWritted = true;
			} else {
				error_log( '['.date('Y-M-d H:i:s').'] '. $data . "\n" );
			}

			return $this;
		}

	}
