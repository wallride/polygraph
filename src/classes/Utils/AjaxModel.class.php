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
 
	class AjaxModel extends Model
	{
		/**
		 * @var AjaxStatusType
		 */
		private $status			= null;
		
		
		/**
		 * @return AjaxModel
		**/
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return AjaxStatusType
		 */
		public function getStatus()
		{
			return $this->status;
		}
		
		/**
		 * @param AjaxStatusType $type
		 * @return AjaxModel
		 */
		public function setStatus(AjaxStatusType $type)
		{
			$this->status = $type;
			parent::set(
				'status',
				array(
					'code'		=> $type->getName(),
				) 
			);

			return $this;
		}
                
                public function setExtended($v = true){
                    if ($v)
                        parent::set('extendedView', true);
                    else
                        parent::drop('extendedView');
                    return $this;
                }
		
		/**
		 * Импортирует ошибки из формы и добавляет в нужное место в нужном виде
		 * @param Form $form
		 * @return AjaxModel
		 */
		public function setFormErrors(Form $form)
		{
			$errors = MappedFormErrors::create($form)->getErrors();
			
			if(!$errors)
				return $this;
			
			if( $this->has('errors') )
			{
				$cursor = &$this->get('errors');
				$cursor['form'] = $errors;
			} else {
				$this->set(
					'errors',
					array(
						'form' => $errors
					)
				);
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		**/
		public function set($name, $var)
		{
			$this->vars['data'][$name] = $var;
			
			return $this;
		}
		
		public function get($name)
		{
			return $this->vars['data'][$name];
		}
		
		public function has($name)
		{
			return isset($this->vars['data'][$name]);
		}
		
		/**
		 * @return Model
		**/
		public function drop($name)
		{
			unset($this->vars['data'][$name]);
			
			return $this;
		}
		
	}