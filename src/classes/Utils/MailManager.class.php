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

	final class MailManager {

		/**
		 * @var array
		 */
		private $activeVars				= array();

		/**
		 * @var string
		 */
		private $template				= null;

		/**
		 * @return MailManager
		 */
		public static function create()
		{
			return new self();
		}

		/**
		 * @param string $key
		 * @param mix $value
		 * @return MailManager
		 */
		public function setVar($key, $value)
		{
			if( isset($this->activeVars[$key]) )
				Assert::isEmpty( $this->activeVars[$key] );

			$this->activeVars[$key] = $value;
			return $this;
		}

		/**
		 * @param string $path
		 * @return MailManager
		 */
		public function setTemplate($path)
		{
			$this->template = $path;

			return $this;
		}

		/**
		 * @return string
		 */
		public function getTemplate()
		{
			return $this->template;
		}

		/**
		 * @return string
		 */
		protected function resolveTemplate() {

			$model = Model::create();
			$template = $this->template;

			foreach ($this->activeVars as $key => $value) {
				$model->set($key, $value);
			}

			$viewResolver =
				MultiPrefixPhpViewResolver::create()->
				setViewClassName('SimplePhpView')->
				addPrefix(
					PATH_TEMPLATES.'mail'.DIRECTORY_SEPARATOR
				);

			$view = new PartViewer($viewResolver, $model);

			ob_start();
			$view->view( $template );
			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;

		}

		/**
		 * @param Mail $mail
		 */
		public function send(Mail $mail) {
			$mail->setText(
				$this->resolveTemplate()
			);
			//$mail->send();
		}

	}


?>
