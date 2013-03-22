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
	 * @see also http://www.dklab.ru/lib/PHP_CodeFilter/demo/lib/PHP/CodeFilter.php
	 *
	 */
	class ErrorHandler
	{
		public static function obStart($text)
	    {
			if (defined('__LOCAL_DEBUG__') && __LOCAL_DEBUG__ === true)
				return $text;

			$re = self::getErrorPattern();

		    $p = null;
		    if (!preg_match($re, $text, $p))
		    	return $text;

		    list (, $content, $beforeFile, $error, $msg, $file, $beforeLine, $line, $afterLine, $tail) = $p;

		    // стандартное сообщение об ошибке
		    $messageError = html_entity_decode("$error:  $msg in $file on line $line", ENT_QUOTES, DEFAULT_ENCODING);

		    // текст ошибки включая контекст страницы
		    $_COOKIE	= isset($_COOKIE) ? $_COOKIE : array();
		    $_SESSION	= isset($_SESSION) ? $_SESSION : array();
		    $_GET		= isset($_GET) ? $_GET : array();
		    $_POST		= isset($_POST) ? $_POST : array();
		    $_IP		= isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];

		    $to      = BUGLOVERS;
			$subject = 'bug notification';
			$message = "URI: ".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]."\n".
				"Message:\n".
				"-------\n".
				$messageError."\n".
				"-------\n".
				"Date:  ".date("d-m-y H:i:s")."\n".
				"IP              : ".$_IP."\n".
				"HTTP_REFERER    : ".(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER['HTTP_REFERER'] : 'undefined')."\n".
				'HTTP_USER_AGENT : '.(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'undefined')."\n".
				'HTTP_ACCEPT     : '.(isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : 'undefined')."\n".
				"-------\n".
				"_GET = ".serialize($_GET)."\n".
				"_POST = ".serialize($_POST)."\n".
				"_COOKIE = ".serialize($_COOKIE)."\n".
				"_SESSION_BASE64_SERIALIZE = ".base64_encode(serialize($_SESSION))."\n";

			$headers = 'From: bug <'.MAIL_FROM.'>'."\r\n";

			
			if (!mail($to, $subject, trim($message), $headers))
				return $messageError;
			

		    return $content;
	    }

	    /**
	     * @return string
	     */
	    public static function getErrorPattern()
	    {
	    	$varsion = PHP_VERSION;
	    	
	    	if(
	    		version_compare($varsion, '5.3.0') >= 0
	    	){
	    		return self::getErrorPattern53();
	    	}
	    	
	    	return self::getErrorPattern52();
	    }
	    
	    /**
	     * До версии 5.3
	     * @return string
	     */
	    public static function getErrorPattern52()
	    {
	    	$prefix = ini_get('error_prepend_string');
		    $suffix = ini_get('error_append_string');

		    return '{^(.*)(' .
	            preg_quote($prefix, '{}') .
	            "<br />\r?\n<b>(\w+ error)</b>: \s*" .
	            '(.*?)' .
	            ' in <b>)(.*?)(</b>' .
	            ' on line <b>)(\d+)(</b><br />' .
	            "\r?\n" .
	            preg_quote($suffix, '{}') .
	            ')()$' .
	        '}us';
	    }
	    
		/**
	     * После версии 5.3
	     * @return string
	     */
	    public static function getErrorPattern53()
	    {
	    	$prefix = ini_get('error_prepend_string');
		    $suffix = ini_get('error_append_string');

		    return '{^(.*)('.preg_quote($prefix, '{}') .
	            "(\w+ error)</b>: \s*" .
	            '(.*?)' .
	            ' in )(.*?)(' .
	            ' on line)(\d+)(' .
	            "" . preg_quote($suffix, '{}') .
	            ')()$' .
	        '}us';
	    }
	}
?>