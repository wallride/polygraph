<?php
// это не дает последнейверсии всего проекта. досадно но ладно.
define('PROJECT_VERSION', '0.1');

// system settings
/**
 * TIP:
 *   Passing in the value -1 will show every possible error,
 *   even when new levels and constants are added in future PHP versions.
 */
ini_set('magic_quotes_gpc', 'Off');

setlocale(LC_CTYPE, "ru_RU.UTF8");
setlocale(LC_TIME, "ru_RU.UTF8");
date_default_timezone_set('Europe/Moscow');

// session
ini_set('session.name', 'sid');
//session_save_path('/tmp/sess/'.PROJECT_ID);

define('DEFAULT_ENCODING', 'UTF-8');
mb_internal_encoding(DEFAULT_ENCODING);
mb_regex_encoding(DEFAULT_ENCODING);

define('AREA_NAME', 'area');
define('DEFAULT_CONTROLLER', 'main');

// paths
define('PATH_CLASSES', PATH_BASE.'src'.DS.'classes'.DS);
define('PATH_INTERFACES',  PATH_BASE.'src'.DS.'classes'.DS.'Interfaces'.DS);
define('PATH_EXCEPTIONS',  PATH_BASE.'src'.DS.'classes'.DS.'src'.DS.'Exceptions'.DS);



//Rabbit config
define('RABBIT_DEFAULT_HOST', 'localhost');
define('RABBIT_DEFAULT_PORT', '5672');
define('RABBIT_DEFAULT_USER', 'guest');
define('RABBIT_DEFAULT_PASS', 'guest');
define('RABBIT_DEFAULT_VHOST', '/');


set_include_path(
	// current path
	get_include_path().PATH_SEPARATOR

	.PATH_INTERFACES.PATH_SEPARATOR
	.PATH_EXCEPTIONS.PATH_SEPARATOR
	.PATH_CLASSES.'Auto'.PATH_SEPARATOR
	.PATH_CLASSES.'Auto'.DS.'Business'.PATH_SEPARATOR
	.PATH_CLASSES.'Auto'.DS.'Proto'.PATH_SEPARATOR
	.PATH_CLASSES.'Auto'.DS.'DAOs'.PATH_SEPARATOR

	.PATH_CLASSES.'Business'.PATH_SEPARATOR
	.PATH_CLASSES.'Forms'.PATH_SEPARATOR
	.PATH_CLASSES.'DAOs'.PATH_SEPARATOR
	.PATH_CLASSES.'Proto'.PATH_SEPARATOR
	.PATH_CLASSES.'Security'.PATH_SEPARATOR

	.PATH_CLASSES.'Filters'.PATH_SEPARATOR

	.PATH_CLASSES.'Bases'.PATH_SEPARATOR

	.PATH_CLASSES.'GUI'.PATH_SEPARATOR
	.PATH_CLASSES.'GUI'.DS.'Helpers'.PATH_SEPARATOR
	.PATH_CLASSES.'GUI'.DS.'Widgets'.PATH_SEPARATOR
	.PATH_CLASSES.'GUI'.DS.'Widgets'.DS.'Html'.PATH_SEPARATOR
	.PATH_CLASSES.'GUI'.DS.'Widgets'.DS.'Menu'.PATH_SEPARATOR
	.PATH_CLASSES.'GUI'.DS.'Widgets'.DS.'Corridor'.PATH_SEPARATOR
	.PATH_CLASSES.'GUI'.DS.'Widgets'.DS.'Manage'.PATH_SEPARATOR
	.PATH_CLASSES.'GUI'.DS.'Widgets'.DS.'Report'.PATH_SEPARATOR

	.PATH_CLASSES.'Flow'.PATH_SEPARATOR
	/*
	 * Crm flow block
	 */
	.PATH_CLASSES.'Flow'.DS.'crm'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'crm'.DS.'corridor'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'crm'.DS.'client'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'crm'.DS.'order'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'crm'.DS.'formfield'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'crm'.DS.'register'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'crm'.DS.'salary'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'crm'.DS.'cabinet'.PATH_SEPARATOR
	/*
	 * Crm flow block /
	 */

	/*
	 * Task flow block
	 */
	.PATH_CLASSES.'Flow'.DS.'tm'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'tm'.DS.'report'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'tm'.DS.'task'.PATH_SEPARATOR
	/*
	 * Task flow block /
	 */

	/*
	 * Billing flow block
	 */
	.PATH_CLASSES.'Flow'.DS.'billing'.PATH_SEPARATOR
	/*
	 * Billing flow block /
	 */

	/*
	 * Auth flow block
	 */
	.PATH_CLASSES.'Flow'.DS.'auth'.PATH_SEPARATOR
	.PATH_CLASSES.'Flow'.DS.'auth'.DS.'company'.PATH_SEPARATOR
	/*
	 * Auth flow block /
	 */



	.PATH_CLASSES.'Commands'.DS.PATH_SEPARATOR

	.PATH_CLASSES.'Utils'.PATH_SEPARATOR
	.PATH_CLASSES.'Utils'.DS.'File'.DS.PATH_SEPARATOR
	.PATH_CLASSES.'Utils'.DS.'Authenticators'.DS.PATH_SEPARATOR
	.PATH_CLASSES.'Utils'.DS.'Notifier'.DS.PATH_SEPARATOR
	.PATH_CLASSES.'Utils'.DS.'HttpClientHandlers'.DS.PATH_SEPARATOR

	.PATH_CLASSES.'Interfaces'.DS.PATH_SEPARATOR
	.PATH_CLASSES.'Exceptions'.DS.PATH_SEPARATOR

	/*
	 * Common controllers
	 */
	.PATH_BASE.'src'.DS.'handlers'.DS.'common'.PATH_SEPARATOR
);