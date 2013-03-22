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



	final class GUIUtils extends StaticFactory
	{
		/**
		 * @param mixed $object
		 * @return string
		 */
		public static function objectToString($object)
		{
			if (is_object($object)) {
				if ($object instanceof Stringable)
					return (string) $object->toString();
				elseif( $object instanceof Named )
					return (string) $object->getName();
				elseif ($object instanceof Identifiable)
					return get_class($object).'['.$object->getId().']';
			} else
				return (string)$object;
		}
	}
