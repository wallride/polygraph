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
 
	abstract class AbstractJsonHttpClientHandler extends AbstractHttpClientHandler
	{
		/**
		 * Результат декодирования json
		 * @var array
		 */
		protected $result				= null;
		
		/** (non-PHPdoc)
		 * @see src/classes/Utils/HttpClientHandlers/AbstractHttpClientHandler::isError()
		 * @return boolean 
		 */
		public function isError()
		{
			return (boolean)
				( $this->result && parent::isError() );
		}
		
		/**
		 * @return array | null
		 */
		public function getResult()
		{
			return $this->result;
		}
		
		/** (non-PHPdoc)
		 * @see src/classes/Utils/HttpClientHandlers/AbstractHttpClientHandler::run()
		 * @return AbstractJsonHttpClientHandler
		 */
		public function run($expires=Cache::EXPIRES_MEDIUM)
		{
			parent::run($expires);

			$this->result = json_decode(
				$this->response->getBody(),
				true
			);
			
//			if( 
//				$this->result === null
//			){
//				$message = _('Cold not decode json from response body!');
//				$this->logger->write($message);
//				
//				throw new WrongStateException(
//					__METHOD__.': '.
//					$message
//				);
//			}

//			if(
//				isset( $this->result['status'] ) &&
//				isset( $this->result['status']['code'] ) && 
//				$this->result['status']['code'] != HttpStatus::CODE_200
//			){
//				$message = _('Json has bad status code! Code: ').$this->result['status']['code'];
//				$this->logger->write(
//					$message
//				);
//				
//				throw new WrongStateException(
//					__METHOD__.': '.
//					$message
//				);
//			}
			
			
			return $this;
		}
		
	}