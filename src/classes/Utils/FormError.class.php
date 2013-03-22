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
 

	final class FormError
	{
		/**
		 * @var Form
		 */
		private $form	= null;
		
		private $alert	= array();

		/**
		 * @param Form $form
		 * @return FormError
		 */
		public static function create(Form $form)
		{
			return new self($form);
		}
		
		public function __construct(Form $form)
		{
			$this->form = $form;
			$this->process();
		}

		/**
		 * @param string $primitiveName
		 * @return <null, string>
		 */
		public function getTextualErrorFor($primitiveName)
		{
			if (isset($this->alert[$primitiveName]))
				return implode(', ', $this->alert[$primitiveName]);
			else
				return null;
		}
		
		/**
		 * @return array
		 */
		public function getTextualErrors()
		{
			$list = array();
			
			foreach ( $this->alert as $prm => $errors )
			{
				$list[$prm] = $this->getTextualErrorFor($prm);
			}
			
			return $list;
		}

		/**
		 * @return FormError
		 */
		private function process()
		{
			foreach ($this->form->getErrors() as $prmName => $wrongType) {

				try {
					$prmObj = $this->form->get($prmName);

					if ($prmObj->isRequired() && $wrongType == BasePrimitive::MISSING) {
						$this->alert[$prmName][] = _('обязательное для заполнения');
					}

					if ($wrongType == BasePrimitive::WRONG) {
						$this->alert[$prmName][] = _('некорректное значение');

						if ($prmObj instanceof RangedPrimitive
							&& $prmObj->getMin()
						){
							if ($prmObj instanceof PrimitiveString)
								$this->alert[$prmName][] = _('минимально допустимое количество символов ').$prmObj->getMin();
							if ($prmObj instanceof PrimitiveInteger)
								$this->alert[$prmName][] = _('минимально допустимое значение ').$prmObj->getMin();
						}

						if ($prmObj instanceof RangedPrimitive
							&& $prmObj->getMax()
						){
							if ($prmObj instanceof PrimitiveString)
								$this->alert[$prmName][] = _('максимально допустимое количество символов ').$prmObj->getMax();
							if ($prmObj instanceof PrimitiveInteger)
								$this->alert[$prmName][] = _('максимально допустимое значение ').$prmObj->getMax();
						}

					}

				} catch (MissingElementException $e){/**/}
			}

			return $this;
		}
		
		/**
		 * Пропишет дефалтовые ошибки в форме
		 * @param Form $form
		 * @return void
		 */
		public static function targetize(Form $form)
		{
			$formErrors = self::create($form);
	    	foreach ( $formErrors->getTextualErrors() as $prm => $label )
	    	{
	    		if( !$form->getWrongLabel( $prm ) )
	    			$form->addWrongLabel($prm, $label);
	    	}
	    	
	    	return /* void */;
		}
	} 