<?php
/**
 * Created by JetBrains PhpStorm.
 * User: georgy
 * Date: 05.09.11
 * Time: 13:51
 * To change this template use File | Settings | File Templates.
 */
 
	interface IAuthenticator
	{

		/**
		 * @abstract
		 * @param User $user
		 * @return void
		 */
		public function setUser(User $user);

		/**
		 * @abstract
		 * @return User
		 */
		public function getUser();

		/**
		 * @abstract
		 * @param Timestamp $timestamp
		 * @return void
		 */
		public function setExpireDate(Timestamp $timestamp);

		/**
		 * @abstract
		 * @return boolean
		 */
		public function isAuthenticated();

	}