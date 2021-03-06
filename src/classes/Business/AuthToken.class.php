<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1 at 2011-02-07 20:39:13                           *
 *   This file will never be generated again - feel free to edit.            *
 *****************************************************************************/

	class AuthToken extends AutoAuthToken implements Prototyped, DAOConnected, IArrayable
	{
		/**
		 * @return AuthToken
		**/
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return AuthTokenDAO
		**/
		public static function dao()
		{
			return Singleton::getInstance('AuthTokenDAO');
		}
		
		/**
		 * @return ProtoAuthToken
		**/
		public static function proto()
		{
			return Singleton::getInstance('ProtoAuthToken');
		}
		
		// your brilliant stuff goes here
		
		public function getActualIpv4()
		{
			$ip = $this->getIp();
			
			if(
				($pos = mb_strripos($ip, '.') )
			)
			{
				$ip = mb_substr($ip, 0, $pos).'.0';
			}
			
			return $ip;
		}
		
		/**
		 * Валиден ?
		 * @return boolean
		 */
		public function isValid()
		{
			$now = Timestamp::makeNow();
			$ip = ( SecurityManager::me()->getIp() )
				? IpAddress::create( SecurityManager::me()->getIp() )
				: null;
			$nip = ( $this->getIp() )
				? IpAddress::create( $this->getActualIpv4() )
				: null;
			$ipNetwork = ( $nip )
				? IpNetwork::create( $nip, 24)
				: null;
			return 
				(
					(
						!$this->getExpireDate() || 
						Timestamp::compare($this->getExpireDate(), $now) > -1
					) &&
					(
						(
							!$ipNetwork
						) ||
						(
							$ip &&
							$ipNetwork->contains( $ip )
						)
					)
				);
		}
		
		/**
		 * Быстрое создание ключа
		 * @param User $user
		 * @param Integer (minutes) $expire
		 * @return AuthToken
		 */
		public static function make(User $user, $minutes=null)
		{
			$key = new self();
			
			if($minutes)
			{
				Assert::isInteger($minutes);
				
				$expire = Timestamp::create('+ '.$minutes.' minutes');
				
				$key->setExpireDate($expire);
				$key->setLifeTimeMinutes($minutes);
			}
				
			
			
			$key->setUser($user);
			
				
			if(
				$ip = SecurityManager::me()->getIp()
			)
				$key->setIp($ip);
			$key->setCreateDate(
				Timestamp::makeNow()
			);
			
			self::dao()->add($key);
			
			return $key;
		}
		
		/**
		 * Быстрая очистка старых токенов
		 * @param AuthToken $token
		 * @return AuthToken
		 */
		public static function clean(AuthToken $token)
		{
			return AuthToken::dao()->clean($token);
		}
		
			
		/**
		 * @return array
		 */
		public function toArray()
		{
			return array(
				'utoken' => $this->getId(),
			);
		}
	}
?>