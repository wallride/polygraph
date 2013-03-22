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


	abstract class GenericUrlWorker extends Singleton implements IUrlWorker
	{
		protected $params;
		protected $baseDomain = DOMAIN;
		protected $protocol = PROTOCOL;

		/** (non-PHPdoc)
		 * @see src/classes/Interfaces/IUrlWorker::setParams()
		 * @return GenericUrlWorker
		 */
		public function setParams(array $params)
		{
			$this->params = $params;
			return $this;
		}
	
		/**
		 *
		 * @param string $host
		 * @return GenericUrlWorker
		 */
		public function setHost($host)
		{
			$host = (string) $host;
			
			if(
				$pos = mb_strpos($host, '://')
			){
				$this->protocol = mb_substr($host, 0, $pos);
				
				if(
					$posrs = mb_strpos($host, '/', $pos+3 )
				){
					$this->baseDomain = mb_substr($host, ($pos+3), ( $posrs - ($pos+3) ) );
				} else {
					$this->baseDomain = mb_substr($host, $pos+3);
				}
			}

			return $this;
		}
		
		/* (non-PHPdoc)
		 * @see src/classes/Interfaces/IUrlWorker::setBaseDomain()
		 */
		public function setBaseDomain($value)
		{
			$this->baseDomain = (string) $value;
			
			return $this;
		}
		
		/* (non-PHPdoc)
		 * @see src/classes/Interfaces/IUrlWorker::setBaseDomain()
		 */
		public function setProtocol($value)
		{
			$this->protocol = (string) $value;
			
			return $this;
		}		
		
	
		/**
		 * @return string
//		 */
//		public function __toString()
//		{
//			return $this->toString();
//	   	}
	   	
	   	/**
	   	 * @return string
	   	 */
	   	protected function makeHost()
	   	{
	   		return $this->protocol.'://'.$this->baseDomain.'/';
	   	}
	   	
	   	/**
	   	 * @return string
	   	 */
	   	protected function makeParamsString(array $params)
	   	{
	   		$array = array();
	
			foreach ($params as $key => $value) {
				if ($value)
					$array[] = $key.'='.urlencode($value);
			}
	
			if ($array)
				return (string) join('&',$array);
	   	}
	
		/** 
		 * @see core/Base/Stringable::toString()
		 * @return string
		 */
		public function toString()
		{
			$host = $this->makeHost();
			
			if( $paramsString = $this->makeParamsString($this->params) )
				return (string) $host.'?'.$paramsString;
	
			return (string) $host;
		}
	}

?>
