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
/* Отправка емейлов */

	class EmailTransport implements INotifierTransport
	{

		/**
		 * @return Email
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
			//error_log('try to send');
			/*
			 * проходимся по получателям
			 */
			foreach($message->getRecipient() as $recipient)
			{
				//Устанавливаем конкретного пользователя (кот. будет в шаблоне), компанию и урл
				$message->setVar('user', $recipient);
				if (false !== $message->getCompany()) $message->setVar('company', $message->getCompany());
				//Получаем сгенерированный текст для емейла
				$content = $message->getContent( NotificationType::wrap(NotificationType::TYPE_EMAIL)->getName() );
				//Если все ок и шаблон удачно сгенерен, отправляем письмо
				if (false != $content)
				{
					if (
						$message->getCompany() &&
						$recipient instanceof User
					)
						$userSettingValue = UserNotification::dao()->getUserSetting(
							$recipient,
							$message->getCompany(),
							$message->getType(),
							NotificationType::wrap(NotificationType::TYPE_EMAIL)
						);
					else
						$userSettingValue = true;

					//Проверки :
					if ( !in_array($message->getType()->getId(), NotificationType::wrap(1)->getEmailNameList()) // настройка не доступна для отключения
						 || $userSettingValue)
					{
						// собираем емеил отправителя
						$fromEmail = 'Motivator Robot <info@mymotivator.ru>';
						if( defined('MAIL_FROM') ) {
							$fromEmail = MAIL_FROM;
							if( defined('MAIL_FROM') ) {
								$fromEmail = MAIL_FROM_NAME.' <'.MAIL_FROM.'>';
							}
						}

						$mail =
							Mail::create()
								->setFrom( $fromEmail )
								->setSubject( $message->getSubject() )
								->setContentType( 'text/html' )
								->setEncoding( 'utf-8' )
								->setText( $content );
						//Емейлы для адресов из конфига и прочих, для которых нет объектов в БД, а просто передан email
						if (is_string( $recipient ))
						{
							try
							{
								//LoggerUtils::log('Letter from '.$from->getEmail().' to '.$recipient.': '."\r\n");
								$mail->setTo( $recipient );
								$mail->send();
							}
							catch(Exception $e)
							{
								LoggerUtils::log('Error send letter to '.$recipient.': '."\r\n".$e->getMessage()."\r\n".$e->getTraceAsString());
							}
							continue;
						}
						if ($recipient instanceof Invite)
						{
							try
							{
								//LoggerUtils::log('(invite)Letter from '.$from->getEmail().' to '.$recipient->getEmail().': '."\r\n");
								$mail->setTo( $recipient->getEmail() );
								$mail->send();
							}
							catch(Exception $e)
							{
								LoggerUtils::log('Error send invite letter to '.$recipient->getEmail().': '."\r\n".$e->getMessage()."\r\n".$e->getTraceAsString());
								continue;
							}
						}
						else
						{
							//Высылаем по всем подтверждённым email для этого пользователя, зарегистрированных в БД
							
							/* @var User $recipient */
							try
							{
								//находим подтвержденный емейл
								$email = UserContact::dao()->getByConfirmedByType($recipient, ContactType::typeEmail());
								$email = $email->getValue();
								//LoggerUtils::log('Letter from '.$from->getEmail().' to '.$email->getValue().': '."\r\n");
							}
							//емейл не найден, переходим к слeдующему
							catch (ObjectNotFoundException $e)
							{
								LoggerUtils::log(__METHOD__.'Error send letter to '.$recipient->getEmail().' (seems like user has no confirmed email): ');
								continue;
							}
							
							//Посылаем по главному email этого пользователя
							try {
								$mail->setTo( $recipient->getEmail() );
								$mail->send();
							} catch(Exception $e) {
								LoggerUtils::log('Error send letter to '.$recipient->getEmail().': '."\r\n".$e->getMessage()."\r\n".$e->getTraceAsString());
								continue;
							}
						}
						
					}
				}
				else{
					LoggerUtils::log(__METHOD__.' Empty email message for '.$recipient->getEmail());
				}
			}
		}

	}

?>
