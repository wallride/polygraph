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



	final class CookieUtils extends StaticFactory {

		/**
		 * Возвращает содержание куки по его id
		 *
		 * @param String $id
		 * @return String
		 */
		public static function getById($id) {
			if(
				isset($_COOKIE[$id])
			) {
				return $_COOKIE[$id];
			}
			
			return null;
		}

		/**
		 * Отправляет header для проставления cookie
		 *
		 * @param Cookie $cookie
		 * @return void
		 */
		public static function setCookie(Cookie $cookie) {
			$cookie->httpSet();
			$_COOKIE[$cookie->getId()]=$cookie->getValue();
		}


		/**
		 * @param Cookie $cookie
		 * 
		 * @return void
		 */
		public static function unsetCookie(Cookie $cookie) {
			$cookie->setDomain(COOKIE_DOMAIN);
			$cookie->setMaxAge(-1);
			$cookie->httpSet();
			unset($_COOKIE[$cookie->getName()]);
		}





	}



?>