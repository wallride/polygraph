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

	final class NotifierRecipient //implements ArrayAccess
	{
		private $recipient = array();
		private $company = null;

		/**
		 * @return NotifierRecipient
		 */
		public static function create()
		{
			return new self();
		}

		public function add(User $user)
		{
			$this->recipient[] = $user;
			return $this;
		}
		public function addCompany(Company $company)
		{
			$this->company = $company;
			return $this;
		}
		public function addCriteria()
		{

		}
		public function getList()
		{
			return $this->recipient;
		}
		public function getCompany()
		{
			return $this->company;
		}
	}

?>