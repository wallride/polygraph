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

	class SecurityManager extends Singleton implements Instantiatable  {

		// Название hash ключа идинтификатора пользователя
		const COOKIE_USER_IDETIFICATION				=	'utoken';

		// Название hash ключа ip
		const COOKIE_USER_IDETIFICATION_IP			=	'extensip';

		// Секретный ключь :-)
		const CRYPT_SALT							=	'bestofthebestofthebestsalt';

		private $isSecureLogin						= false;

		/**
		 * @return SecurityManager
		 */
		static public function me() {
			return Singleton::getInstance(__CLASS__);
		}

		protected function __construct() {
			parent::__construct();
		}


		/**
		 * Логиним пользователя
		 *
		 * @param String $email
		 * @param String $password
		 * @param Boolean $isRemember
		 *
		 * @return void
		 * @deprecated
		 * @throws WrongStateException
		 */
		public function login($email, $password, $isRemember=false) {
			throw new WrongStateException(
				__METHOD__ . ': '.
				_('this function is deprecated!')
			);
		}

		/**
		 * @param boolean $orly
		 * @return SecurityManager
		 */
		public function setSecureLogin($orly=false)
		{
			$this->isSecureLogin = (true == $orly);

			return $this;
		}

		/**
		 * @return boolean
		 */
		public function isSecureLogin()
		{
			return ($this->isSecureLogin == true);
		}


		/**
		 * @param User $user
		 * @param boolean $isRemember
		 */
		public function setUser(User $user, $isRemember=false)
		{
			//Записываем куки
			//return $this->setCookies($user, $isRemember);
		}

		/**
		 * Разлогиниваем пользователя
		 *
		 * @return void
		 */
		public function logout() {
			//$this->unsetCookies();
			

			return /*void*/;
		}

		/**
		 * Авторизован ли пользователь
		 * @return Boolean
		 **/
		public function isLoggedIn() {

			if( $this->getUser() instanceof User ) {
				// Если включена дополнительная защита
				//if( $this->isSecureLogin() ) {
				//	if( !$this->isValidCookie() ) {
				//		// Очищаем данные в куки
				//		$this->unsetCookies();
				//		return false;
				//	}
				//	return true;
				//}
				return true;
			}

			return false;
		}

		/**
		 * Возвращает id из сесси пользователя
		 *
		 * @return string || null
		 */
		private function getUserIdFromCookie() {
			//$userId = self::decrypt(
			//	CookieUtils::getById(self::COOKIE_USER_IDETIFICATION)
			//);
			$utokenId = CookieUtils::getById(self::COOKIE_USER_IDETIFICATION);
			$userId = null;
			
			if(
				Assert::checkUniversalUniqueIdentifier( $utokenId )
			){
				
				try{
					
					$utoken = AuthToken::dao()->getById($utokenId);
					
					if(
						$utoken->isValid()
					){
						$userId = $utoken->getUserId();
					}
					
				} catch ( ObjectNotFoundException $e ){/**/}
				
			}
			
			
			return $userId;
		}

		/**
		 * Возвращает id из сесси пользователя
		 *
		 * @return String || null
		 */
		public function getUserIpFromCookie() {
			$userIp = self::decrypt(
				CookieUtils::getById(self::COOKIE_USER_IDETIFICATION_IP)
			);
			return $userIp;
		}


		/**
		 * Возвращает авторизованного пользователя
		 *
		 * @return User || null
		 */
		public function getUser() {
			try {
				$userid=$this->getUserIdFromCookie();
				Assert::isTrue(
					(Assert::checkScalar($userid) && Assert::checkUniversalUniqueIdentifier($userid) )
				);
				return User::dao()->getById($userid);

			}
			catch (ObjectNotFoundException $e) {/**/}
			catch (WrongArgumentException $e) {/**/}

			return null;
		}

		/**
		 * Не подделана ли куки ? :-)
		 * Провека валидности Ip пользователя на зашифрованный Ip в куки
		 *
		 * @return Boolean || null
		 */
		public function isValidCookie() {
			$cookieIp=$this->getUserIpFromCookie();
			return ( $cookieIp == $this->getIp() );
		}

		/**
		 * Обнуляем авторизационные данные!
		 *
		 * @return void
		 */
		private function unsetCookies() {

			$cookie = Cookie::create(self::COOKIE_USER_IDETIFICATION);
			$cookie->setPath('/');
			CookieUtils::unsetCookie($cookie);

			if($this->isSecureLogin() ) {
				$cookie = Cookie::create(self::COOKIE_USER_IDETIFICATION_IP);
				$cookie->setPath('/');
				CookieUtils::unsetCookie($cookie);
			}

			return /*void*/;
		}

		/**
		 * @param User $user
		 * @param boolean $isRemember
		 *
		 * @return void
		 */
		private function setCookies(User $user, $isRemember=false) {

			$expire = 0; // До закрытия браузера

			if($isRemember) {
				$expire=2 * 604800; // Неделя * 2 = 2 недели :-)
			}

			// Сохраняем id в куки
			$cookieUserId=Cookie::create(self::COOKIE_USER_IDETIFICATION);
			$cookieUserId->setDomain(COOKIE_DOMAIN);
			$cookieUserId->setPath('/');
			$cookieUserId->setValue( self::encrypt( $user->getId() ) );
			$cookieUserId->setMaxAge($expire);
			CookieUtils::setCookie($cookieUserId);


			if($this->isSecureLogin() ) {
				// Сохраняем ip в куки
				$cookieUserIp=Cookie::create(self::COOKIE_USER_IDETIFICATION_IP);
				$cookieUserIp->setDomain(COOKIE_DOMAIN);
				$cookieUserIp->setPath('/');
				$cookieUserIp->setValue( self::encrypt( $this->getIp() ) );
				$cookieUserIp->setMaxAge($expire);
				CookieUtils::setCookie($cookieUserIp);
			}

			return /*void*/;
		}

		/**
		 * Дешифруем
		 * @param string $string
		 * @return string
		 */
		public static function decrypt($string) {
			return trim(
				mcrypt_decrypt(
					MCRYPT_RIJNDAEL_256,
					self::CRYPT_SALT,
					base64_decode($string),
					MCRYPT_MODE_ECB,
					mcrypt_create_iv(
						mcrypt_get_iv_size(
							MCRYPT_RIJNDAEL_256,
							MCRYPT_MODE_ECB
						),
						MCRYPT_RAND
					)
				)
			);
		}

		/**
		 * Шифруем
		 * @param string $string
		 * @return string
		 */
		public static function encrypt($string) {
			return trim(
				base64_encode(
					mcrypt_encrypt(
						MCRYPT_RIJNDAEL_256,
						self::CRYPT_SALT,
						$string,
						MCRYPT_MODE_ECB,
						mcrypt_create_iv(
							mcrypt_get_iv_size(
								MCRYPT_RIJNDAEL_256,
								MCRYPT_MODE_ECB
							),
							MCRYPT_RAND
						)
					)
				)
			);
		}
		
		/**
		 * Проверка ИНН на валидность
		 * @see http://ru.wikipedia.org/wiki/%D0%98%D0%9D%D0%9D
		 * @param string $inn - 10-ти или 12-ти значное число
		 * @return boolean
		 */
		public static function isValidINN($inn)
		{
			//  все нули удовлетворяют формуле
			if ( (int)$inn === 0 ) {
				return false;
			}
	
			$i = (string)$inn;
			if ( !preg_match('/^\d*$/',$i) ) {
				return false;
			}
	
			if ( mb_strlen($i) == 10 ) {
				$s = ( ( 2*$i[0] + 4*$i[1] + 10*$i[2] + 3*$i[3] + 5*$i[4] + 9*$i[5] + 4*$i[6] + 6*$i[7] + 8*$i[8]  ) % 11 ) % 10;
				return $s == $i[9];
			}
	
			if ( mb_strlen($i) == 12 ) {
				$s  = ( ( 7*$i[0] + 2*$i[1] + 4*$i[2] + 10*$i[3] + 3*$i[4] + 5*$i[5] + 9*$i[6] + 4*$i[7] + 6*$i[8] + 8*$i[9]  ) % 11 ) % 10;
				$s2 = ( ( 3*$i[0] + 7*$i[1] + 2*$i[2] + 4*$i[3] + 10*$i[4] + 3*$i[5] + 5*$i[6] + 9*$i[7] + 4*$i[8] + 6*$i[9] + 8*$i[10] ) % 11 ) % 10;
				return ($s == $i[10] && $s2 == $i[11]);
			}
			
			return false;
		}

		/**
		 * Ip адрес
		 * @return string
		 */
		public function getIp() {
			return (isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR']);
		}

		/**
		 * Обновляем информацию о сесси пользователя в БД.
		 *
		 * @param User $user
		 * @return void
		 */
		public function updateUserState() {
			if(
				$this->isLoggedIn()
			)
			{
				$user = $this->getUser();
				$user->setLastActivity(Timestamp::makeNow() );
				$user->setLastIp($this->getIp() );
				User::dao()->save($user);
			}

			return /*void*/;
		}

		/**
		 * @return Company
		 */
		public function getCompanyByAlias()
		{
			try {
				
				Assert::isNotNull($_SERVER);
				$alias = $this->getAlias($_SERVER);
				Assert::isNotNull($alias);
				
				$company = Company::dao()->getByAlias($alias);
				
				return $company;
			}
			catch (ObjectNotFoundException $e) {/**/}
			catch (WrongArgumentException $e) {/**/}
			
			return null;
		}

		/**
		 * @return User
		 */
		public function getUserByAlias()
		{
			try {

				Assert::isNotNull($_SERVER);
				$alias = $this->getAlias($_SERVER);
				Assert::isNotNull($alias);

				$user = User::dao()->getByAlias($alias);

				return $user;
			}
			catch (ObjectNotFoundException $e) {/**/}
			catch (WrongArgumentException $e) {/**/}

			return null;
		}

		/**
		 * @param array $serverInfo
		 * @return string|NULL
		 */
		public function getAlias( $serverInfo )
		{
			$matches = array();
			preg_match("/^(?:www\.)?([A-Za-z-0-9]+)(?:\.[A-Za-z-0-9]+)(?:\.[A-Za-z]+)/", $serverInfo['HTTP_HOST'], $matches);

			if (count($matches)>0)
			{
				return trim($matches[1]);
			}
			
			return null;
		}

		/* Кодирует пароль
		 * @rapam string $password
		 * @return string
		 */
		public function encodePassword( $password, $email )
		{
			return md5( $email.'-secret-'.$password );
		}

	}


?>
