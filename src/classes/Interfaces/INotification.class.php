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
/* $Id$ */

	interface INotification
	{
		/**
		 * @return INotification
		 */
		public function setSubject($subject);

		/**
		 * @return string | Stringable
		 */
		public function getSubject();

		//public function setTemplate(string $template);
		
	}

?>