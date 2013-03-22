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

require '../config/current/config.inc.php';


	try {
		
//		XHProfUtils::begin();
		
		//Start session
		Session::start();

		$request = HttpRequest::create()->
			setGet($_GET)->
			setPost($_POST)->
			setCookie($_COOKIE)->
			setServer($_SERVER)->
			setSession($_SESSION)->
			setFiles($_FILES);
//		if(SecurityManager::me()->isLoggedIn()) {
//			$request->setAttachedVar(
//				'loggedUser',
//				SecurityManager::me()->getUser()
//			);
//		}

		$templatesPaths = array(
			PATH_TEMPLATES,
			PATH_TEMPLATES.'mail/',
		);

		$includePaths = array(
			PATH_CONTROLLERS,
		);

		$rewrites = include PATH_BASE.'config'.DS. 'include'.DS.'config.rewrite.inc.php';
		$rewrite = RouterRewrite::me();
		foreach ($rewrites as $name => $rule)
			$rewrite->addRoute(
				$name,
				$rule
			);

		$application = HttpController::me();
		$application->addIncludePaths($includePaths);
		$application->setTemplatePaths($templatesPaths);

		$application->setRouterRewrite($rewrite);

                $controller = $application->run($request);
                $controller->handleRequest($request);
                
require_once PATH_BASE.'lib/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('../src/tmpl');
$twig = new Twig_Environment($loader, array(
//    'cache' => '/tmp/twig',
));
Delivery::deliverMail($twig, isset($_GET['log-mail']));
echo $controller->getResultHTML($twig); 


		XHProfUtils::end();
			
	} 
	catch (Exception $e) {
		
		LoggerUtils::sendException($e);
	}
