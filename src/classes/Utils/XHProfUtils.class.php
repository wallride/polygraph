<?php
/***************************************************************************
 *   Copyright (C) 2011 by Kutcurua Georgy Tamazievich                     *
 *   email: g.kutcurua@gmail.com, icq: 723737, jabber: soloweb@jabber.ru   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	class XHProfUtils extends StaticFactory
	{
		protected static $silent		= true;

		public static function silent($value)
		{
			self::$silent = ( true == $value);
		}

		/**
		 * @return HttpController
		 */
		public static function begin()
		{
			if(
				!self::$silent ||
				(
					self::$silent &&
					isset($_GET['x'])
				)
			)
			{
				# Инициализируем профайлер - будем считать и процессорное время и потребление памяти
				xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
			}
		}

		/**
		 * @return HttpController
		 */
		public static function end()
		{
			if(
				!self::$silent ||
				(
					self::$silent &&
					isset($_GET['x'])
				)
			)
			{
				# Останавливаем профайлер
				$xhprof_data = xhprof_disable();

				# Сохраняем отчет и генерируем ссылку для его просмотра
				include_once PATH_XHPROF.'xhprof_lib.php';
				include_once PATH_XHPROF.'xhprof_runs.php';
				$xhprof_runs = new XHProfRuns_Default();
				$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_test");

				$uri = '';
				if( isset($_SERVER) && $_SERVER['HTTP_HOST']  )
					$uri = $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];

				$report = 'Report('.$uri.'): http://xhprof.'.DOMAIN.'/index.php?run='.$run_id.'&source=xhprof_test';
				error_log(
					$report
				);
			}

		}


	}