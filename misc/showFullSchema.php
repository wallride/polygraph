<?php
//include '../config/current/config.inc.php';


include PATH_CLASSES.'Auto'.DIRECTORY_SEPARATOR.'schema.php';

file_put_contents(
	'db_structure.sql',
	$schema->toDialectString(
		DBPool::me()->getLink()->getDialect()
	)
);
?>
