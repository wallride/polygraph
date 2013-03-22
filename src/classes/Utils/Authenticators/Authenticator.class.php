<?php
/**
 * Created by JetBrains PhpStorm.
 * User: georgy
 * Date: 05.09.11
 * Time: 15:19
 * To change this template use File | Settings | File Templates.
 */
 
	final class Authenticator extends Singleton implements IAuthenticator, Instantiatable {

		/**
		 * @var IAuthenticator
		 */
		protected $authenticator			= null;

		/**
		 * @static
		 * @return Authenticator
		 */
		public static function me()
		{
			return Singleton::getInstance(__CLASS__);
		}
		
		protected function __construct()
		{
			/*
			 * Default authenticator
			 */
			$this->authenticator = CookieAuthenticator::create();
		}


		/**
		 * @throws WrongArgumentException
		 * @param IAuthenticator $authenticator
		 * @return Authenticator
		 */
		public function setAuthenticatorWorker(IAuthenticator $authenticator)
		{
			if( $authenticator instanceof Authenticator )
				throw new WrongArgumentException(
					'authenticator can not be class of Authenticator'
				);
			
			$this->authenticator = $authenticator;

			return $this;
		}

		/**
		 * @param User $user
		 * @return void
		 */
		public function setUser(User $user)
		{
			$this->authenticator->setUser($user);

			return $this;
		}

		/**
		 * @return User
		 */
		public function getUser()
		{
			return $this->authenticator->getUser();
		}

		/**
		 * @param Timestamp $timestamp
		 * @return Authenticator
		 */
		public function setExpireDate(Timestamp $timestamp)
		{
			$this->authenticator->setExpireDate($timestamp);

			return $this;
		}

		/**
		 * @return boolean
		 */
		public function isAuthenticated()
		{
			return $this->authenticator->isAuthenticated();
		}
	}
