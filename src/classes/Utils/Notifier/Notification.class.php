<?php 
/***************************************************************************
 *   Copyright (C) 2010 by E.Goleva										   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
/*
 * Класс уведомлений всех для типов
 */

	class Notification implements INotification
	{
		//переменные, передаваемые в шаблон
		private $vars			= array();

		//получатели
		private $recipient		= array();

		//компания
		private $company 		= null;

		//тип события, инициировавший отправку сообщений
		private $type			= null;

		//заголовок
		private $subject		= null;

		//шаблон
		private $template		= null;

		//id target'а
		private $targetId		= null;
		
		//тип target'а
		private $targetType		= null;

		//урд для нотисов
		private $url		= null;

		/**
		 * @return Notification
		 */
		public static function create()
		{
			return new self();
		}
		/*
		 * Установить переменные
		 * @param string $key
		 * @param string $value
		 * @return Notification
		 */
		public function setVar($key, $value)
		{
			if (isset($this->vars[$key])) unset($this->vars[$key]);
			$this->vars[$key] = $value;
			return $this;
		}
		/*
		 * Установить шаблон
		 * @param string $template
		 * @return Notification
		 */
		public function setTemplate( $template )
		{
			$this->template = $template;			
			return $this;
		}

		/*
		 * Установить тип события, инициировавший отправку сообщений
		 * @param NotificationType $notificationType
		 * @return Notification
		 */
		public function setType(NotificationType $notificationType)
		{
			if( null == $notificationType )
				Assert::isEmpty( $notificationType );

			$this->type = $notificationType;
			return $this;
		}
		/*
		 * Установить заголовок
		 * @param string $subject
		 * @return Notification
		 */
		public function setSubject( $subject )
		{
			$this->subject = $subject;

			return $this;
		}

		/*
		 * Добавление получателей
		 * @param Object $object (of user or invite)
		 * @return Notification
		 */
		public function addRecipient( $object )
		{
			$this->recipient[] = $object;
			return $this;
		}
		/*
		 * Добавление списка получателей
		 * @param array of $user
		 * @return Notification
		 */
		public function addRecipientList( $array )
		{
			foreach($array as $recipient)
			{
				$this->recipient[] = $recipient;
			}
			return $this;
		}
		/*
		 * Установить компанию
		 * @param Company $company
		 * @return Notification
		 */
		public function setCompany( $company )
		{
			$this->company = $company;
			return $this;
		}
		/*
		 * Установить targetId (для нотисов)
		 * @param uuid $targetId
		 * @return Notification
		 */
		public function setTargetId( $targetId )
		{
			$this->targetId = $targetId;
			return $this;
		}
		/*
		 * Установить targetId (для нотисов)
		 * @param NoticeTargetType $targetType
		 * @return Notification
		 */
		public function setTargetType( NoticeTargetType $targetType )
		{
			$this->targetType = $targetType;
			return $this;
		}
		/*
		 * Установить урл для перехода
		 * @param string $url
		 * @return Notification
		 */
		public function setUrl($url)
		{
			$this->url = $url;
			return $this;
		}
		/*
		 * Очистить переменную, передаваемую в шаблон
		 * @param NoticeTargetType $targetType
		 * @return Notification
		 */
		public function clearVar($key)
		{
			if (isset($this->vars[$key]))
					unset($this->vars[$key]);
			return $this;
		}
		/*
		 * Возвращает получателей сообщения
		 * @return array of user objects
		 */
		public function getRecipient()
		{
			return $this->recipient;
		}
		/*
		 * Возвращает компанию
		 * @return company
		 */
		public function getCompany()
		{
			return $this->company;
		}
		/*
		 * Возвращает TargetId
		 * @return uuid
		 */
		public function getTargetId()
		{
			return $this->targetId;
		}
		/*
		 * Возвращает TargetType
		 * @return NoticeTargetType
		 */
		public function getTargetType()
		{
			return $this->targetType;
		}
		/*
		 * Возвращает заголовок
		 * @return string
		 */
		public function getSubject()
		{
			return $this->subject;
		}
		/*
		 * Возвращает переменные для шаблона
		 * @return array
		 */
		public function getVar()
		{
			return $this->vars;
		}
		/*
		 * Возвращает тип события
		 * @return NotificationType
		 */
		public function getType()
		{
			return $this->type;
		}
		/*
		 * Возвращает сгенерированный шаблон
		 * @param NotificationType @prefix - prefix (folder) где лежат сообщения в зависимости от транспорта
		 * @return string
		 */
		public function getContent( $prefix )
		{
			return $this->resolveTemplate( $prefix );
		}
		/*
		 * Возвращает url
		 * @return string
		 */
		public function getUrl()
		{
			return $this->url;
		}
		/**
		 * Генерит шаблон
		 * @return string
		 */
		protected function resolveTemplate( $prefix )
		{
			$model = Model::create();

			if (null == $this->template)
			{
				$this->template = $this->getType()->getTemplate();
			}

			foreach ($this->vars as $key => $value)
			{
				$model->set($key, $value);
			}

			$model->set('subject', $this->subject);

			$viewResolver =
				MultiPrefixPhpViewResolver::create()->
				setViewClassName('SimplePhpView')->
				addPrefix(
					PATH_TEMPLATES.$prefix.DIRECTORY_SEPARATOR
				);

			$view = new PartViewer($viewResolver, $model);

			ob_start();
			try
			{
				$view->view( $this->template );
			}
			catch(Exception $e)
			{
				error_log (__METHOD__.' COULD NOT RENDER VIEW template = '.var_export(PATH_TEMPLATES.$prefix.DIRECTORY_SEPARATOR.$this->template, true).' Exception: '.$e->__toString());
				return false;
			}
			$contents = ob_get_contents();
			ob_end_clean();
			
			return $contents;

		}

		
	}

?>