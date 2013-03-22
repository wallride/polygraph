<?php
/**
 * Created by JetBrains PhpStorm.
 * User: georgy
 * Date: 05.09.11
 * Time: 14:08
 * To change this template use File | Settings | File Templates.
 */
 
	class CookieAuthenticator implements IAuthenticator {

		/**
		 * @var Timestamp
		 */
		protected $expireDate						= null;

		// Название hash ключа идинтификатора пользователя
		const COOKIE_USER_IDETIFICATION				=	'utoken';

		// Секретный ключь :-)
		const CRYPT_SALT							=	'bestofthebestofthebestsalt';

		/**
		 * @static
		 * @return CookieAuthenticator
		 */
		public static function create()
		{
			return new self();
		}

		/**
		 * @param User $user
		 * @return CookieAuthenticator
		 */
		public function setUser(User $user)
		{
			$expire = 0;

			if( $this->expireDate instanceof Timestamp )
			{
				$expire = $this->expireDate->toStamp();
			}

			// Сохраняем id в куки
			$cookieUserId=Cookie::create(self::COOKIE_USER_IDETIFICATION);
			$cookieUserId->setDomain(DOMAIN_NAME);
			$cookieUserId->setPath('/');
			$cookieUserId->setValue( self::encrypt( $user->getId() ) );
			$cookieUserId->setMaxAge($expire);
			CookieUtils::setCookie($cookieUserId);

			return $this;
		}

		/**
		 * @return User
		 */
		public function getUser()
		{
			 $id = CookieUtils::getById(self::COOKIE_USER_IDETIFICATION);
			if( is_scalar($id) )
				return User::dao()->getById($id);
		}

		/**
		 * @return boolean
		 */
		public function isAuthenticated()
		{
			$id = CookieUtils::getById(self::COOKIE_USER_IDETIFICATION);

			if( is_scalar($id) )
			{
				try{

					$user = User::dao()->getById($id);
					return ($user instanceof User);

				} catch (Exception $e) {/*_*/}
			}

			return false;
		}

		/**
		 * @param Timestamp $timestamp
		 * @return void
		 */
		public function setExpireDate(Timestamp $timestamp)
		{
			$this->expireDate = $timestamp;

			return $this;
		}


		/**
		 * Дешифруем
		 * @param string $string
		 * @return string
		 */
		protected static function decrypt($string) {
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
		protected static function encrypt($string) {
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
	}
