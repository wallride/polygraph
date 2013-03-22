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
	 * @example
	 * 		AuthHttpClientHandler::create()->setId({auth_key})->run();
	 * 
	 * После чего клиент авторизуется
	 * 
	 * @throws Exception
	 *
	 */
	class AuthHttpClientHandler extends AbstractJsonHttpClientHandler
	{
		
		/**
		 * @return AuthHttpClientHandler
		 */
		public static function create()
		{
			return new self();
		}
		
		/**
		 * Ключь авторизации
		 * @var string
		 */
		protected $id					= null;
		
		protected function makeServiceName()
		{
			return 'auth_http_client';
		}
		
		/** (non-PHPdoc)
		 * @see src/classes/Utils/HttpClientHandlers/AbstractHttpClientHandler::makeHttpRequest()
		 * @return HttpRequest
		 */
		protected function makeHttpRequest()
		{
			Assert::isNotNull($this->id );
			$request = HttpRequest::create();
			
			// Метод передачи 
			$request->setMethod(
				HttpMethod::post()
			);
			
			//Url шлюза
			$request->setUrl(
				HttpUrl::create()->parse(
					GATEWAY_AUTH,
					true
				)
			);
			
			$request->setPostVar('id', $this->id);
			$request->setPostVar('fmt', 'json');
			
			return $request;
		}
		
		/**
		 * @param string $id
		 * @return AuthHttpClientHandler
		 */
		public function setId($id)
		{
			Assert::isScalar(
				$id,
				__METHOD__.': id must be a scalar value!'
			);
			
			$this->id = $id;
			
			return $this;
		}
		
		/**
		 * @return string
		 */
		public function getId()
		{
			return $this->id;
		}
		
		
		/**
		 * @see src/classes/Utils/HttpClientHandlers/AbstractJsonHttpClientHandler::run()
		 * @return AuthHttpClientHandler
		 */
		public function run($expires=Cache::EXPIRES_MEDIUM)
		{
			parent::run($expires);
			
			if(
				!$this->isError()
			){
				try{
					$this->auth();
				} catch (Exception $e) {
					$this->logger->write( $e->__toString() );
					throw $e;
				}
				
			}
			
			return $this;
		}
		
		protected function auth()
		{			
			$result = $this->getResult();
			$data = isset( $result['data'] )
				? $result['data']
				: null;
				
			
			$userId = (
				$data &&
				isset( $data['user_id'] )
			)
				? $data['user_id'] 
				: null ;
							
			$isRemember = (
				$data &&
				isset( $data['is_remember'] )
			) 
				? (boolean) $data['is_remember'] 
				: false ;
			
			Assert::isScalar(
				$userId,
				__METHOD__.': '.
				_('user_id must be a scalar value! given ').gettype($userId)
			);	
			
			try{
				$user = User::dao()->getById($userId);
				
				
				// Если таки прорвались то авторизуем
				SecurityManager::me()->setUser($user, $isRemember);
				
			} catch (Exception $e) {
				throw $e;
			}				
			
		}
		
		/**
		 * @return string | null
		 */
		public function getReturnUrl()
		{
			return 
				(
					isset( $this->result['data'] ) &&
					isset( $this->result['data']['return_url'] )
				)
					? $this->result['data']['return_url']
					: null;			
		}
		
	}