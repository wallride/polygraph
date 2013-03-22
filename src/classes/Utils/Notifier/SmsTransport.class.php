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
/* Ставит смс в очередь */

	class SmsTransport implements INotifierTransport
	{

		/**
		 * @return NotifierRecipient
		 */
		public static function create()
		{
			return new self();
		}
		/*
		 * @param INotification $message
		 * @param  $from
		 * @return void
		 */
		public function send( INotification $message, $from )
		{
			/*
			 * проходимся по получателям
			 */
			foreach($message->getRecipient() as $recipient)
			{
				//Устанавливаем конкретного пользователя (кот. будет в шаблоне)
				$message->setVar('user', $recipient);
				//Получаем сгенерированный текст для емейла
				$content = $message->getContent( NotificationType::wrap(NotificationType::TYPE_SMS)->getName() );
				
				if (false != $content)
				{
					//Если все ок и шаблон удачно сгенерен, отправляем письмо
					if ( !in_array($message->getType()->getId(), NotificationType::wrap(1)->getSmsNameList())// настройка не доступна для отключения
							 || (null != $message->getCompany() //передана компания и у пользователя выставлена галочка отправлять ему емейл по этому событию
							 && $recipient->getNotificationSetting()->getByType($message->getType(), NotificationType::wrap(NotificationType::TYPE_SMS), $message->getCompany())))
						{
							try
							{
								//находим подтвержденный мобильный
								$phone = UserContact::dao()->getByConfirmedByType($recipient, ContactType::typeMobile());
								//ставим в очередь на отправку
								try
								{
									$sms = SmsQueue::create()
											->setAuthor( $from )
											->setUser( $recipient )
											->setPhone( $phone->getValue() )
											->setMessage( $content );
									SmsQueue::dao()->add( $sms );
								}
								catch (Exception $e)
								{}
							}
							//мобильный не найден, переходим к слeдующему
							catch (ObjectNotFoundException $e)
							{
								continue;
							}
					}
				}
			}
		}
	}

?>