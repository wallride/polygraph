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
 
	final class WebDAVClient 
	{
		/**
		 * @var CurlHttpClient
		 */
		private $client				= null;
		
		/**
		 * @var HttpUrl
		 */
		private $url				= null;
		
		/**
		 * @param string $uri
		 * @return WebDAVClient
		 */
		public static function create($uri)
		{
			return new self($uri);
		}
		
		public function __construct($uri)
		{
			$this->client = CurlHttpClient::create();
			$this->url = HttpUrl::create()->parse($uri, true);
			
			$this->client->setMaxRedirects(5);
			$this->client->setNoBody(true);
			$this->client->setTimeout(190);			
		}
		
		/**
		 * @return HttpRequest
		 */
		protected function makeBaseRequest()
		{
			$request = $this->url->toHttpRequest();
			$request->setUrl( $this->url );
			
			return $request;
		}
		
		/**
		 * @param string $path
		 * @return boolean
		 */
		protected function realMkcol($path)
		{
			Assert::isTrue(
				(mb_substr($path, 0, 1) == DIRECTORY_SEPARATOR ),
				_('path must be started "'.DIRECTORY_SEPARATOR.'"')
			);
			
			$request = $this->makeBaseRequest();
			$request->setMethod( HttpMethod::mkcol() );
			
			$url = clone $this->url;
			$url->setPath($path);
			
			$request->setUrl($url);
			
			
			$response = $this->client->send($request);
			
			// Если ответ 20x
			if(
				$response->getStatus()->getId() > 199 && 
				$response->getStatus()->getId() < 211
			)
				return true;
				
			error_log(
				__METHOD__.': '.
				$response->getStatus()->toString()
			);
				
			return false;
		}
		
		/**
		 * @param string $path
		 * @return boolean
		 */
		public function mkcol($path)
		{
			return $this->realMkcol($path);
		}
		
		/**
		 * Закачка файла на WebDav
		 * @param string $davPath
		 * @param string $filePath
		 * @return boolean
		 */
		public function put($davPath, $filePath)
		{
			Assert::isTrue(
				(
					file_exists($filePath) &&
					is_readable($filePath)
				),
				_($filePath . ' can not read!')
			);
			
			$request = $this->makeBaseRequest();
			$request->setMethod( HttpMethod::put() );
			
			$url = clone $this->url;
			$url->setPath($davPath);
			
			$request->setUrl($url);
			
			$io = fopen($filePath, 'r');

			$this->client->setOption(CURLOPT_INFILE, $io);
			$this->client->setOption(CURLOPT_INFILESIZE, filesize($filePath) );
			
			$response = $this->client->send( $request );
			
			// Если ответ 20x
			if(
				$response->getStatus()->getId() > 199 && 
				$response->getStatus()->getId() < 211
			)
				return true;
				
			error_log(
				__METHOD__.': '.
				$response->getStatus()->toString()
			);
				
			return false;
		}
		
		/**
		 * @param unknown_type $path
		 * @throws ObjectNotFoundException
		 * @return mixed
		 */
		public function get($path)
		{
			$request = $this->makeBaseRequest();
			$request->setMethod( HttpMethod::get() );
			
			$url = clone $this->url;
			$url->setPath($path);
			
			$request->setUrl($url);
			
			$response = $this->client->send( $request );
			
			// Если ответ 20x
			if(
				$response->getStatus()->getId() > 199 && 
				$response->getStatus()->getId() < 211
			)
				return $response->getBody();
				
			error_log(
				__METHOD__.': '.
				$response->getStatus()->toString()
			);
				
			throw new ObjectNotFoundException(
				__METHOD__.': '.
				$path . _(' not found!')
			);
		}
		
		/**
		 * @param string $path
		 * @return boolean
		 */
		public function exist($path)
		{
			$request = $this->makeBaseRequest();
			$request->setMethod( HttpMethod::head() );
			
			$url = clone $this->url;
			$url->setPath($path);
			
			$request->setUrl($url);
			
			$response = $this->client->send( $request );
			
			// Если ответ 20x
			if(
				$response->getStatus()->getId() > 199 && 
				$response->getStatus()->getId() < 211
			)
				return true;
				
			return false;
		}
		
		/**
		 * @param string $path
		 * @return boolean
		 */
		public function delete($path)
		{
			$request = $this->makeBaseRequest();
			$request->setMethod( HttpMethod::delete() );
			
			$url = clone $this->url;
			$url->setPath($path);
			
			$request->setUrl($url);
			
			$response = $this->client->send( $request );
			
			// Если ответ 20x
			if(
				$response->getStatus()->getId() > 199 && 
				$response->getStatus()->getId() < 211
			)
				return true;
				
			error_log(
				__METHOD__.': '.
				$response->getStatus()->toString()
			);
				
			return false;
		}
		
	
		/**
		 * @param string $srcPath
		 * @param string $dstPath
		 * @return boolean
		 */
		public function copy($srcPath, $dstPath)
		{
			
			Assert::isTrue(
				(mb_substr($srcPath, 0, 1) == DIRECTORY_SEPARATOR ),
				_('srcPath must be started "'.DIRECTORY_SEPARATOR.'"')
			);
			
			Assert::isTrue(
				(mb_substr($dstPath, 0, 1) == DIRECTORY_SEPARATOR ),
				_('dstPath must be started "'.DIRECTORY_SEPARATOR.'"')
			);
			
			$request = $this->makeBaseRequest();
			$request->setMethod( HttpMethod::copy() );
			
			$url = clone $this->url;
			$url->setPath($srcPath);
			
			$urlDst = $url->getScheme().'://'.$url->getHost().$dstPath;
			
			$request->setUrl($url);
			
			$request->setHeaderVar('Destination', $urlDst );
			
			$response = $this->client->send( $request );
			
			// Если ответ 20x
			if(
				$response->getStatus()->getId() > 199 && 
				$response->getStatus()->getId() < 211
			)
				return true;
				
			error_log(
				__METHOD__.': '.
				$response->getStatus()->toString()
			);
				
			return false;
		}
		
		/**
		 * @param string $srcPath
		 * @param string $dstPath
		 * @return boolean
		 */
		public function move($srcPath, $dstPath)
		{
			
			Assert::isTrue(
				(mb_substr($srcPath, 0, 1) == DIRECTORY_SEPARATOR ),
				_('srcPath must be started "'.DIRECTORY_SEPARATOR.'"')
			);
			
			Assert::isTrue(
				(mb_substr($dstPath, 0, 1) == DIRECTORY_SEPARATOR ),
				_('dstPath must be started "'.DIRECTORY_SEPARATOR.'"')
			);
			
			$request = $this->makeBaseRequest();
			$request->setMethod( HttpMethod::move() );
			
			$url = clone $this->url;
			$url->setPath($srcPath);
			
			$urlDst = $url->getScheme().'://'.$url->getHost().$dstPath;
			
			$request->setUrl($url);
			
			$request->setHeaderVar('Destination', $urlDst );
			
			$response = $this->client->send( $request );
			
			// Если ответ 20x
			if(
				$response->getStatus()->getId() > 199 && 
				$response->getStatus()->getId() < 211
			)
				return true;
				
			error_log(
				__METHOD__.': '.
				$response->getStatus()->toString()
			);
				
			return false;
		}
		
	}