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

	class WebDavFileWorker extends Singleton implements IFileWorker, Instantiatable
	{

		/**
		 * @return WebDavFileWorker
		 */
		public static function me()
		{
			return Singleton::getInstance( __CLASS__ );
		}

		public function add(File $object)
		{

			try{

				Assert::isNotNull(
					$object->getTmpPath(),
					__METHOD__.': '.
					_('tmpPath must be setted!')
				);

				$fullPath = $object->getTmpPath();

				$lastDirectorySeparatorPosition = mb_strrpos($fullPath, DIRECTORY_SEPARATOR);
				Assert::isNotFalse(
					$lastDirectorySeparatorPosition,
					__METHOD__.': '.
					_('Unsupported file name ').'"'.$fullPath.'" !'
				);

				if( !$object->getFileName() )
				{
					/*
					 * Имя файла
					 */
					$fileName = mb_substr($fullPath, ($lastDirectorySeparatorPosition + 1) );

					/*
					 * Уникальное имя файла
					 */
					$uniqueFileName = FileUtils::makeUniqueName($fileName);
					$object->setFileName($uniqueFileName);
				}


				/*
				 * Если имя не проставленно, ставим имя файла.
				 */
				if(
					!$object->getName()
				)
					$object->setName( $object->getFileName() );

				$client = WebDAVClient::create( $object->getServer()->getUri() );

				if(
					!$client->put($object->getFullPathForSystem(), $fullPath)
				)
					throw new WrongStateException(
						__METHOD__.': '.
						$object->getFullPathForSystem() .' '. _('can not put file into webdav!')
					);

				unset($client);

			}catch (BaseException $e){
				error_log( $e->__toString() );
				throw $e;
			}

			return /*void*/;
		}

		/* (non-PHPdoc)
		 * @see src/classes/Interfaces/IFileWorker::save()
		 */
		public function save(File $object)
		{
			/*
			 * @todo Сделать нормальную логику сохранения файлов
			 *  К примеру может поменятся имя файла в системе :-)
			 */
			return /*void */ ; //$this->add($object);
		}

		/* (non-PHPdoc)
		 * @see src/classes/Interfaces/IFileWorker::drop()
		 */
		public function drop(File $object)
		{
			try{

				$client = WebDAVClient::create( $object->getServer()->getUri() );

				if(!$client->delete( $object->getFullPathForSystem() ) )
					throw new WrongStateException(
						__METHOD__.': '.
						$object->getFullPathForSystem(). _(' can not deleted from webdav!')
					);

			}catch (BaseException $e){
				error_log( $e->__toString() );
				throw $e;
			}

			return /*void*/;
		}


		/**
		 * @see src/classes/Interfaces/IFileWorker::ping()
		 * @return boolean
		 */
		public function ping(File $object)
		{
			try{
				$client = WebDAVClient::create( $object->getServer()->getUri() );
				return $client->exist( $object->getFullPathForSystem() );
			}catch (BaseException $e){/**/}

			return false;
		}

		/** (non-PHPdoc)
		 * @see src/classes/Interfaces/IFileWorker::getBlob()
		 * @return mixed
		 */
		public function getBlob(File $object)
		{
			try{
//				LoggerUtils::log(__METHOD__.' '.__LINE__.' '.$object->getServer()->getUri());
				$client = WebDAVClient::create( $object->getServer()->getUri() );
				return $client->get( $object->getFullPathForSystem() );
			}catch (BaseException $e){
				error_log( $e );
			}

			return null;
		}

	}
