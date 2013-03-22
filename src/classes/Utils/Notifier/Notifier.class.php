<?php
/***************************************************************************
 *   Created by E.Goleva                                                   *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
/*
 * Универсальный отправитель уведомлений всех типов
 */

	final class Notifier
	{
		//автор сообщения
		private $author = null;

		/**
		 * собс-но объект сообщения
		 * @var Notification
		 */
		private $message = null;

		//список транспортов
		private $notifierTransport = array();

		//список получателей
		private $recipient = array();


		/**
		 * @return Notifier
		 */
		public static function create()
		{
			return new self();
		}

		/*
		 * Добавление автора сообщения
		 * @param User $user
		 * @return Notifier
		 */
		public function addAuthor(User $user)
		{
			$this->author = $user;
			return $this;
		}

		/*
		 * Добавление сообщения
		 * @param Notification $message
		 * @return Notifier
		 */
		public function addMessage(Notification $message)
		{
			$this->message = $message;
			return $this;
		}

		/*
		 * Добавление транспортов
		 * @param INotifierTransport $notifierTransport
		 * @return Notifier
		 */
		public function addTransport(INotifierTransport $notifierTransport)
		{
			$this->notifierTransport[] = $notifierTransport;
			return $this;
		}
		/*
		 * Отправка сообщений
		 * @return void
		 */
		public function send()
		{
			/*
			 * Если трансопрты не переданы, по умолчанию добавляем все
			 */
			if( null == $this->notifierTransport )
			{
				$this->notifierTransport[] = EmailTransport::create();
				$this->notifierTransport[] = SmsTransport::create();
				$this->notifierTransport[] = NoticeTransport::create();
			}
			//Не передано сообщение
			if( null == $this->message )
			{
				Assert::isEmpty( $this->message );
			}
			//Не переданы получатели
			if( null == $this->recipient )
			{
				Assert::isEmpty( $this->recipient );
			}
			//Если автор не передан, ставим системного
			if( null == $this->author )
			{
				$this->author = User::dao()->getById( User::ID_SERVICE );
			}
			/*
			 * Рассылаем по всем установленным типам транспорта
			 */
			foreach($this->notifierTransport as $transport)
			{
				$transport->send( $this->message, $this->author);
			}

			if(
				$this->message &&
				( $recipients = $this->message->getRecipient() )
			){
				foreach ( array_merge($recipients, array($this->author)) as $recipient )
				{
					if(
						$recipient instanceof IAMQPExchangeable &&
						AMQPEventScope::chain()->getSize() > 0
					){
						AMQPEventScope::chain()->setTopic(
							AMQPEventTopic::create()->widgets()->notices()
						);
						AMQPEventScope::chain()->publish($recipient);
					}
				}

			}
		}


	}


?>
