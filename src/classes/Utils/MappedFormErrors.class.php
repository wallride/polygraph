<?php
/***************************************************************************
 *   Copyright (C) 2011 by Mikhail Zelenov, Kutcurua Georgy Tamazievich    *
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
	 * Класс объертка для ошибок
	 * Ошибки для фронта отдаем в читабельном для человека виде.
	 */
	class MappedFormErrors {
		
		/**
		 * @var array
		 */
		protected $map = array(
			0		=> 		'unknow',
			1 		=> 		'wrong',
			2		=> 		'empty',
			
			// And other ...
		);
		
		/**
		 * @var Form
		 */
		protected $form;
		
		/**
		 * Create
		 * @param Form $form
		 */
		public static function create(Form $form) {
			return new self($form);
		}
		
		/**
		 * @param Form $form
		 */
		public function __construct( Form $form )
		{
			$this->form = $form;
		}
		
		/**
		 * @param Form $form
		 * @return MappedFormErrors
		 */
		public function setForm(Form $form)
		{
			$this->form = $form;
			
			return $this;
		}
		
		/**
		 * @return Form
		 */
		public function getForm()
		{
			return $this->form;
		}
		
		/**
		 * @param array $map
		 * @return MappedFormErrors
		 */
		public function setMap(array $map)
		{
			$this->map = array_merge( $this->map, $map );
			
			return $this;
		}
		
		/**
		 * @return array
		 */
		public function getErrors()
		{
			$errors = $this->form->getErrors();
			
			if(!$errors)
				return $errors;
			
			$map = $this->map;
			
			return array_map(
				function ($val) use ($map)
				{
					return ( isset( $map[$val] ) )
						? $map[$val] 
						: $val ;
				},
				$errors
			);
		}
	}