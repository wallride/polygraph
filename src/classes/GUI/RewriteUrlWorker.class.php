<?php
/***************************************************************************
 *   Copyright (C) 2011 by Kutcurua Georgy Tamazievich                     *
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
	 * 
	 	$href = Href::create()->setWorker(
				RewriteUrlWorker::me()
			)->setParams(
				array(
					'oid' => 'sfsdfsd',
					'id' => '2cb5cf3e-8616-11e0-b259-6f6e70687000',
					'area' => 'main',
					'routerName' => 'company'
				)
			);
			
			var_dump( $href->toString() );
	 */

	class RewriteUrlWorker extends GenericUrlWorker
	{
		
		/**
		 * Роутер
		 * @var Router
		 */
		protected $router				= null;
		
		/**
		 * @return RewriteUrlWorker
		 */
		public static function me()
		{
			return self::getInstance(__CLASS__);
		}
		
		
		/**
		 * @param Router $router
		 * @return RewriteUrlWorker
		 */
		public function setRouter(Router $router)
		{
			$this->router = $router;
			
			return $this;
		}
		
		
		/**
		 * @see src/classes/GUI/GenericUrlWorker::toString()
		 * @return string
		 */
		public function toString()
		{
			$router = $this->router;
			$params = $this->params;
			
			if(
				!$router
			)
				$router = RouterRewrite::me();

			
			$routerName = null;
			
			if(
				isset( $params['routerName'] )
			){
				$routerName = $params['routerName'];
				unset( $params['routerName'] );
			}
				
			
			Assert::isNotNull(
				$routerName,
				__METHOD__.': '.
				'You must specifie "routerName" in params!'	
			);
			
			$url = $router->assembly($params, $routerName);
			
			$request = HttpRequest::create()->setServerVar('REQUEST_URI', $url);
			
			$currentRouter = $router->getRoute($routerName);
			$rewritedParams = $currentRouter->match($request);
			
			$params = array_diff($params, $rewritedParams);
			
			$sep = '?';
			
			if( mb_strstr($url, '?') )
				$sep = '&';
				
			if( $paramsString = $this->makeParamsString($params) )
				$url.=$sep.$paramsString;
			
			if(
				!$url
			)
				$url = parent::toString();
				
			return $url;
		}
		
		
		
	}