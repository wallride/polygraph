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
/* Запись уведомлений в базу */

class NoticeTransport implements INotifierTransport
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
		//$message->clearVar('user');
		
		$content = $message->getContent( NotificationType::wrap(NotificationType::TYPE_NOTICE )->getName() );
		if (false != $content)
		{
			try
			{
				//Создаем сам нотис в базе
				$notice = Notice::create()
					->setAuthor( $from )
					->setSubject( $message->getSubject() )
					->setMessage( $content )
					->setTargetId( $message->getTargetId() )
					->setTargetType( $message->getTargetType() )
					->setRegisterDate( Timestamp::makeNow() )
					->setUrl( $message->getUrl() );
				Notice::dao()->add( $notice );				


				$topic = AMQPEventTopic::create()->widgets()->notices();
				//добавляем привязки к нему у всех переданных пользователей
				foreach($message->getRecipient() as $recipient)
				{
					$userNotice = UserNotice::create()
							->setNotice( $notice )
							->setUser( $recipient )
							->setCompany( $message->getCompany() )
							->setIsRead( false )
							->setRegisterDate( Timestamp::makeNow() );
					UserNotice::dao()->add( $userNotice );

					AMQPEventScope::me()->add(
						AMQPEventUtils::makeNoticeEvent(
							$from, 
							$recipient,
							$topic,
							array(
								'object' => UserNotice::dao()->getJsonUserNotice( $userNotice )
							)
						)
					);
					
				}
				
			}
			catch (Exception $e)
			{}
		}
	}
}

?>