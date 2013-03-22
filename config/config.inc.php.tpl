<?php
/*
 * файл локальных настроек
 *
 * продублировать этот файл и переименовать в config.inc.php
 */

define('DS',DIRECTORY_SEPARATOR);
define('PATH_BASE', realpath(dirname(__FILE__).DS.'..'.DS).DS);

// debug
define('__LOCAL_DEBUG__', true);

// Mobivision
define('MOBIVISION_LOGIN', 'motivator');
define('MOBIVISION_PASSWORD', 'Jshyt6Gc');

error_reporting(-1);
ini_set('display_errors', true);

define('BUGLOVERS', 'g.kutsurua@gmail.com');

// Path to onPHP root
// If onPHP-lib is in Project you can set relative path
//   define('PATH_ONPHP', realpath(PATH_BASE.'../../onphp/trunk'));
// Or you can set full path
define('PATH_ONPHP', '/var/www/onphp/');
//define('PATH_ONPHP', realpath(PATH_BASE.'../../../onphp/trunk') );



// strategy including in onPHP, see onphp/misc
define('ONPHP_CLASS_CACHE_TYPE', 'noCache');

// global config
include PATH_ONPHP . 'global.inc.php';
include PATH_BASE . 'config' . DS . 'global.inc.php';


// db
$commonLink = DB::spawn('PgSQL', 'devel', 'devel', '192.168.1.54:5432', 'devel', true, 'utf8');
DBPool::me()->
	setDefault(
		$commonLink
	);

DBPool::me()->
	addLink(
		'dblink_common',
		$commonLink
	);

DBPool::me()->
	addLink(
		'dblink_billing',
		DB::spawn('PgSQL', 'devel', 'devel', '192.168.1.54:5432', 'devel_billing', true, 'utf8')
	);

// cache
Cache::setDefaultWorker('CommonDaoWorker'); // Кэшировать сколько сказанно
//Cache::setDefaultWorker('CacheDaoWorker'); // Кэшировать навечно
//Cache::setDefaultWorker('NullDaoWorker'); // Ничего не кешировать
Cache::setPeer(
	PeclMemcached::create('192.168.1.53', 11211)
);

Cache::appendDaoMap(
	array(
		'AclPermissionDAO' => 'CacheDaoWorker',
		//'BillCompanyLicensesDAO' => 'NullDaoWorker',
		'BillCompanyLicenseDAO' => 'CacheDaoWorker',

	)
);


// для хранения сессий в памяти memcached теперь достаточно в
// любом месте ДО вызова session_start() [можно в конфиге, а можно в index.php]
// прописать
    ini_set('session.save_path', '192.168.1.53:11211');
    Singleton::getInstance('MemcacheSession');

// мыла для фидбека и уведомлений о регистрации
//define('FEEDBACK_EMAIL', 'info@mymotivator.ru,gorbylev@mymotivator.ru');
//define('REGISTER_EMAIL', 'info@mymotivator.ru,gorbylev@mymotivator.ru');
define('FEEDBACK_EMAIL', 'gorbylev@mymotivator.ru');
define('REGISTER_EMAIL', 'gorbylev@mymotivator.ru');
