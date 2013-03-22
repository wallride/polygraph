<?php
/***************************************************************************
 *		Created by Kutcurua Georgy Tamazievich at 09.11.2010 19:20:22
 *		email: g.kutcurua@gmail.com, icq: 723737, jabber: soloweb@jabber.ru
 ***************************************************************************/
/* $Id$ */
 
	class AllowFormFieldsFiltrator implements IFieldsFiltrator
	{
		
		/**
		 * @var array
		 */
		private $list			= array();
		
		/**
		 * @var array
		 */
		private $allowList		= array();
		
		/**
		 * @return AllowFormFieldsFiltrator
		 */
		public static function create()
		{
			return new self();
		} 
		

		/** (non-PHPdoc)
		 * @see src/classes/Interfaces/IFieldsFiltrator::setFields()
		 * @return AllowFormFieldsFiltrator
		 */
		public function setFields($list)
		{
			$this->allowList = $list;
			
			return $this;
		}
		
		/** (non-PHPdoc)
		 * @see src/classes/Interfaces/IFieldsFiltrator::setAllFields()
		 * @return AllowFormFieldsFiltrator
		 */
		public function setAllFields($list)
		{
			$this->list = $list;
			
			return $this;
		}
		
		/**
		 * @return array
		 */
		public function getList()
		{
			$res = array();
			
			foreach($this->allowList as $name) {
				if (isset($this->list[$name])) {
					$res[$name] = $this->list[$name]; 
				}
			}
			return $res;
		}
		
	}