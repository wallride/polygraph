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
 
	class SimpleFileWorker extends Singleton implements IFileWorker, Instantiatable
	{
		/**
		 * @return SimpleFileWorker
		 */
		public static function me()
		{
			return Singleton::getInstance('SimpleFileWorker');
		}
		
		public function add(File $object)
		{
			
			try{
				$old = umask(0);
				
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
				//print $object->getPathForSystem();die();
				
				if(
					// Если директория не создана
					!is_readable( $object->getPathForSystem() )
				){					
					if( !mkdir( $object->getPathForSystem(), 0771, true ) )
						throw new WrongStateException(
							__METHOD__.': '.
							_('Cannot create directory').' "'.$object->getPathForSystem().'"'
						);
				}
				
				if (
					!copy($fullPath, $object->getFullPathForSystem() )
				)	
					throw new WrongArgumentException(
						"can not copy {$fullPath} to {$object->getFullPathForSystem()}"
					);
					
				umask($old);
			}catch (BaseException $e){
				umask($old);
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
			return $this->add($object);
		}
		
		/* (non-PHPdoc)
		 * @see src/classes/Interfaces/IFileWorker::drop()
		 */
		public function drop(File $object)
		{
			try{
				FileUtils::unlink( $object->getFullPathForSystem() );	
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
			return file_exists($object->getFullPathForSystem() );				
		}
		
		/** (non-PHPdoc)
		 * @see src/classes/Interfaces/IFileWorker::getBlob()
		 * @return mixed
		 */
		public function getBlob(File $object)
		{
			return file_get_contents($object->getFullPathForSystem() );
		}
	}