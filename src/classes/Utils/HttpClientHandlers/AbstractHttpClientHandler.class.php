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
 
	abstract class AbstractHttpClientHandler implements HttpClient
	{
		/**
		 * Драйвер клиента
		 * @var HttpClient
		 */
		protected $client						=null;
		
		/**
		 * Тайм аут в секундах
		 * @var integer
		 */
		protected $timeout						=null;
		
		/**
		 * Слудовать ли редиректам?
		 * @var boolean
		 */
		protected $isFollowLocation				=null;
		
		/**
		 * Макимальное кол-во прыжков, совершаемых при редиректе
		 * @var integer
		 */
		protected $maxRedirects					=null;
		
		/**
		 * Вычисляемое значение!
		 * см. AbstractHttpClient::isError
		 * @deprecated
		 */
		protected $isError						=null;
		
		/**
		 * Ответ
		 * @var HttpResponse
		 */
		protected $response						=null;
		
		/**
		 * Логгер
		 * @var Logger
		 */
		protected $logger						=null;
		
			
		public function __construct()
		{
			//Default client is CurlHttpClient
			$this->client = CurlHttpClient::create();
			$this->logger = UniversalLogger::create( $this->makeServiceName() );
		}
		
		/**
		 * Сборка HttpRequest для отправки
		 * @return HttpRequest
		 */
		abstract protected function makeHttpRequest();
		
		/**
		 * Имя сервиса
		 * Необходим для UniversalLogger
		 * @return string
		 */
		abstract protected function makeServiceName();
		
		
		/** (non-PHPdoc)
		 * @see main/Net/Http/HttpClient::setTimeout()
		 * @return AbstractHttpClient
		 */
		public function setTimeout($timeout)
		{
			$this->timeout = (integer) $timeout;
			
			return $this;
		}
		
		/** (non-PHPdoc)
		 * @see main/Net/Http/HttpClient::getTimeout()
		 * @return integer
		 */
		public function getTimeout()
		{
			return $this->timeout;
		}
		
		/** (non-PHPdoc)
		 * @see main/Net/Http/HttpClient::setFollowLocation()
		 * @return AbstractHttpClient
		 */
		public function setFollowLocation($really)
		{
			$this->isFollowLocation = (true == $really);
			
			return $this;
		}
		
		/** (non-PHPdoc)
		 * @see main/Net/Http/HttpClient::isFollowLocation()
		 * @return boolean
		 */
		public function isFollowLocation()
		{
			return (true == $this->isFollowLocation );
		}
		
		/** (non-PHPdoc)
		 * @see main/Net/Http/HttpClient::setMaxRedirects()
		 * @return AbstractHttpClient
		 */
		public function setMaxRedirects($maxRedirects)
		{
			$this->maxRedirects = (integer) $maxRedirects;
			
			return $this;
		}
		
		/** (non-PHPdoc)
		 * @see main/Net/Http/HttpClient::getMaxRedirects()
		 * @return integer
		 */
		public function getMaxRedirects()
		{
			return $this->maxRedirects;
		}
		
		/**
		 * @return HttpClient
		 */
		public function getClient()
		{
			return $this->client;
		}
		
		/**
		 * @param HttpClient $client
		 * @return AbstractHttpClient
		 */
		public function setClient(HttpClient $client)
		{
			$this->client = $client;
			
			return $this;
		}
		
		/**
		 * @param Logger $logger
		 * @return AbstractHttpClientHandler
		 */
		public function setLogger(Logger $logger)
		{
			$this->logger = $logger;
			
			return $this;
		}
		
		/**
		 * @return Logger
		 */
		protected function getLogger()
		{
			return $this->logger;
		}
		
		/**
		 * @return HttpResponse
		 */
		protected function getResponse()
		{
			return $this->response;
		}
		
		/**
		 * Имеются ли ошибки?
		 * @return boolean
		 */
		public function isError()
		{
			if(
				$this->response &&
				$this->response instanceof HttpResponse &&
				$this->response->getStatus()->getId() == HttpStatus::CODE_200
			)
				return false;
			
			return true;
		}
		
		
		/** 
		 * !!! Использовать только метод AbstractHttpClient::run() !!!
		 * 
		 * @see main/Net/Http/HttpClient::send()
		 * @return HttpResponse
		 * @ignore
		 * @deprecated
		 * @see AbstractHttpClient::run()
		 * @uses AbstractHttpClient::run()
		 */
		public function send(HttpRequest $request)
		{
			$this->getClient()->setTimeout(
				$this->getTimeout()
			);
			
			if( $this->isFollowLocation() )
			{
				$this->getClient()->setFollowLocation(
					$this->isFollowLocation()
				);
				
				$this->getClient()->setMaxRedirects(
					$this->getMaxRedirects()
				);
			}
			
			if(
				$this->getClient() &&
				$this->getClient() instanceof CurlHttpClient
			){
				$this->getClient()->setOption( CURLINFO_HEADER_OUT, 1 );
			}
			
			$client = $this->getClient();
			
			try
			{
				$response = $this->getClient()->send($request);
			}
			catch (Exception $e)
			{
				$this->logger->write( 'Error send request: '."\n".$e."\n" );				
			}			
			return $response;
		}
		
		/**
		 * Запускаем на выполнение.
		 * @return AbstractHttpClientHandler
		 */
		public function run($expires=Cache::EXPIRES_MEDIUM)
		{
			try{
				
				$request = $this->makeHttpRequest();
				
				$hash = crc32(serialize( $request ));

				if(
					$expires != Cache::DO_NOT_CACHE &&
					$res = Cache::me()->get($hash) )
				{
					$this->response = unserialize( base64_decode($res) );
					
					if(
						!$this->response &&
						!($this->response instanceof HttpResponse)
					)
						throw new WrongStateException(
							__METHOD__.': '.
							_('Operation unserialize return not implemented of HttpResponse object!!!')
						);
				}else {
					
					$this->response = $this->send($request);
					
					if( $expires != Cache::DO_NOT_CACHE )
						Cache::me()->set($hash, base64_encode(serialize($this->response) ) , $expires);
				}
				
				
				if(
					$this->response->getStatus()->getId() !== HttpStatus::CODE_200
				)
					$this->logger->setError(true);
					
				$headerOut = '';
				if(
					$expires < 0 &&
					$this->getClient() &&
					$this->getClient() instanceof CurlHttpClient
				){
					$headerOut = $this->getClient()->getInfo('request_header');
				}
				
				if(
					( $posts = $request->getPost() ) &&
					is_array( $posts ) &&
					count( $posts )
				){
					$postOut = '';
					foreach ( $posts as $key => $value )
					{
						$postOut.= '&'.$key.'='.$value;
						$postOut = mb_substr($postOut, 1);
					}
						
					$headerOut .= $postOut."\n";
				}
			
				$this->logger->write( 'Request header:'."\n".$headerOut."\n" );

				if(
					$headers = $this->response->getHeaders()
				){
					$headerString ='';
					foreach ( $headers as $headType => $headValue )
						$headerString .= $headType.': '.$headValue."\n";
						
					$this->logger->write( 'Response header:'."\n".$headerString."\n" );
				}

				$this->logger->write( $this->response->getBody() );
				
			} catch (Exception $e){
				
				$this->logger->setError(true);
				$this->logger->write( $e->__toString() );
				throw $e;
			}			
			
			return $this;
		}
		
	}