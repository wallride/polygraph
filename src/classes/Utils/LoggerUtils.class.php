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
 
	class LoggerUtils extends StaticFactory
	{
		/**
		 * Строка для логирования
		 * @param Exception $e
		 * @return string
		 */
		public static function makeExceptionLogString(Exception $e)
		{
			$msg =
				'class: '.get_class($e)."\n"
				.'code: '.$e->getCode()."\n"
				.'message: '.$e->getMessage()."\n\n"
				.$e->getTraceAsString()."\n"
				."\n_POST=".var_export($_POST, true)
				."\n_GET=".var_export($_GET, true)
				.(
					isset($_SERVER['HTTP_REFERER'])
						? "\nREFERER=".var_export($_SERVER['HTTP_REFERER'], true)
						: null
				)
				.(
					isset($_SESSION) ?
						"\n_SESSION=".var_export($_SESSION, true)
						: null
				)
				.(
					isset($_FILES) ?
						"\n_FILES=".var_export($_FILES, true)
						: null
				);
				
			return $msg;
		}
		
		/**
		 * Просто логирование :-)
		 * @param string $msg
		 */
		public static function log($msg, $display=false)
		{
                    if ($display) self::display($msg);
                    error_log($msg);
		}
                
                public static function display($msg){
                    if (__LOCAL_DEBUG__){
                        var_dump($msg);
                        echo '<br/>';
                    }
                    
                }


                /**
		 * Посылаем уведомления разработчикам
		 * @param Exception $e
		 */
		public static function sendException(Exception $e)
		{
			$msg = self::makeExceptionLogString($e);
			
			$uri = ( isset($_SERVER) && isset($_SERVER['HTTP_HOST']) && isset($_SERVER["REQUEST_URI"]) )
					? $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]
					: 'Uri is empty' ;
					
			$uri = 'Bug report: ('.$uri.')';
			
			if (defined('__LOCAL_DEBUG__'))
				echo '<pre>'.$msg.'</pre>';
			else {
				mail(BUGLOVERS, $uri, $msg);
				DBPool::me()->shutdown();
			}
		}
		
		/**
		 * Просто логируем Exception
		 * @param Exception $e
		 */
		public static function logException(Exception $e)
		{
			error_log(
				self::makeExceptionLogString($e)
			);
		}
		
		/**
		 * Логгируем данные для Михаила Зеленова, чтобы не засорять общий error_log
		 * @param string $string
		 */
		public static function writeZelenoff($log) {
			self::write($log, '/tmp/onPHP/mixa.error.log');
		}
		
		public static function write($log, $file) {
			error_log($log ."\r\n", 3, $file);
		}
	}