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

	class HttpApplication
	{

		protected $templatePaths	=	array();

		/**
		 * @var ResponseFormatType
		 */
		protected $responseFormat	=	null;

		/**
		 * @var ModuleType
		 */
		protected $module			= null;

		/**
		 * @var RouterRewrite
		 */
		protected $routerRewrite	= null;

		/**
		 * @return string
		 */
		protected $userClassName		= 'User';

		/**
		 * @return string
		 */
		protected $companyClassName		= 'Company';

		/**
		 * @return string
		 */
		protected $employeeClassName	= 'Employee';

		/**
		 * @return string
		 */
		protected $customerClassName	= 'Customer';

		/**
		 * Молчать при отсутствии статуса
		 * @var boolean
		 */
		protected $statusSilent		= true;
		
		/**
		 * Проверям доступность модулей
		 * @var boolean
		 */
		protected $checkModulesAcl	= true;

		/**
		 * @return HttpApplication
		 */
		public static function create()
		{
			return new self();
		}

		/**
		 * @param ModuleType $module
		 * @return HttpApplication
		 */
		public function setModule(ModuleType $module)
		{
			$this->module = $module;

			return $this;
		}

		/**
		 * @param boolean $boolean
		 * @return HttpApplication
		 */
		public function setStatusSilent($boolean)
		{
			$this->statusSilent = (true == $boolean);

			return $this;
		}
		
		/**
		 * @param boolean $boolean
		 * @return HttpApplication
		 */
		public function setCheckModulesAcl($boolean)
		{
			$this->checkModulesAcl = (true == $boolean);

			return $this;
		}

		/**
		 * @param array $paths
		 * @return HttpApplication
		 */
		public function setTemplatePaths(/* array */$paths)
		{
			Assert::isArray($paths);

			$this->templatePaths = $paths;

			return $this;
		}

		/**
		 * @param string $className
		 * @return HttpApplication
		 */
		public function setUserClassName($className)
		{
			Assert::classExists($className);
			$this->userClassName = $className;

			return $this;
		}

		/**
		 * @param string $className
		 * @return HttpApplication
		 */
		public function setCompanyClassName($className)
		{
			Assert::classExists($className);
			$this->companyClassName = $className;

			return $this;
		}

		/**
		 * @param string $className
		 * @return HttpApplication
		 */
		public function setEmployeeClassName($className)
		{
			Assert::classExists($className);
			$this->employeeClassName = $className;

			return $this;
		}

		/**
		 * @param string $className
		 * @return HttpApplication
		 */
		public function setCustomerClassName($className)
		{
			Assert::classExists($className);
			$this->customerClassName = $className;

			return $this;
		}


		/**
		 * @param array $paths
		 * @return HttpApplication
		 */
		public function addIncludePaths($paths)
		{
			Assert::isArray($paths);

			set_include_path(
				get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $paths)
			);

			return $this;
		}

		/**
		 * @param Controller | ControllerAjax | ControllerXml | ControllerRss $controller
		 * @param HttpRequest $request
		 */
		protected function runController($controller, HttpRequest $request, ResponseFormatType $responseType)
		{
			$interface = $responseType->toInterface();
			if(
				( $controller instanceof $interface )
			){
				return call_user_func(
					array($controller, $responseType->toInterfaceMethod() ),
					$request
				);
			}

			return $controller->handleRequest($request);

		}

		public function setRouterRewrite(RouterRewrite $rewrite)
		{
			$this->routerRewrite = $rewrite;

			return $this;
		}

		/**
		 * @param HttpRequest $request
		 */
		public function run(HttpRequest $request, $level=1)
		{			
			try{

				$this->realRun($request);

			} catch (Exception $e) {

				if(
					!$this->responseFormat->isResorveable()
				){
					$request->setGetVar('area', 'faild');
					$request->setAttachedVar('errorMessage', $e->__toString() );
					
					if($level)
						$this->run($request, --$level);
					else 
						throw $e;

					return /* void */;
				}

				throw $e;
			}
		}

		/**
		 * @param HttpRequest $request
		 * @return ResponseFormatType
		 */
		protected function makeResponseFormat(HttpRequest $request)
		{
			$form = Form::create()->add(
				Primitive::choice('fmt')
					->setList(
						array_combine(
							array_values(
								ResponseFormatType::typeXhtml()->getNameList()
							),
							array_keys(
								ResponseFormatType::typeXhtml()->getNameList()
							)
						)
					)
				->setValue('html')
			);

			$form->import( $request->getPost() );
			$form->importMore( $request->getGet() );

			if( !$form->getErrors() )
			{
				return ResponseFormatType::wrap( $form->getChoiceValue('fmt') );

			}

			return ResponseFormatType::typeXhtml();
		}

		/**
		 * Реально запускаем приложение
		 * @param HttpRequest $request
		 */
		protected function realRun(HttpRequest $request)
		{
			// Default value
			$this->responseFormat = $this->makeResponseFormat($request);

			$this->rewrite($request);

			$this->tokenize($request);			
			
			$this->attacheLoggedEmployee($request);
			
			$controllerName = defined('MAIN_CONTROLLER') ?  MAIN_CONTROLLER : 'main';

			if(
				$request->hasGetVar('area') &&
				ClassUtils::isClassName($request->getGetVar('area') )
			)
				$controllerName = $request->getGetVar('area');
			elseif(
				$request->hasPostVar('area') &&
				ClassUtils::isClassName($request->getPostVar('area') )
			)
				$controllerName = $request->getPostVar('area');

			Assert::classExists($controllerName);

			$controller = new $controllerName;
			$origController = $controller;
			try {

				if(
					(
						$origController instanceof IDependentLoggedEmployee &&
						!(
							$request->hasAttachedVar('loggedEmployee') &&
							$request->getAttachedVar('loggedEmployee') instanceof Employee
						)
					)

				){
					error_log(
						__METHOD__.': '.
						'forbidden - loggedEmployee needed!'
					);
					
					$controller = new forbidden();
				}

				if(
					(
						$origController instanceof IDependentLoggedCustomer &&
						!(
							$request->hasAttachedVar('loggedCustomer') &&
							$request->getAttachedVar('loggedCustomer') instanceof Customer
						)
					)

				){
					error_log(
						__METHOD__.': '.
						'forbidden - loggedCustomer needed!'
					);

					$controller = new forbidden();
				}


				if(
					(
						$origController instanceof IDependentLoggedCompany &&
						!(
							$request->hasAttachedVar('loggedCompany') &&
							$request->getAttachedVar('loggedCompany') instanceof Company
						)
					)

				){
					error_log(
						__METHOD__.': '.
						'forbidden - loggedCompany needed!'
					);
					
					$controller = new forbidden();
				}

				if(
					(
						(
							$origController instanceof IDependentLoggedUser ||
							$origController instanceof IDependentLoggedEmployee ||
							$origController instanceof IDependentLoggedCompany

						) &&						
						!(
							$request->hasAttachedVar('loggedUser') &&
							$request->getAttachedVar('loggedUser') instanceof User
						)
					)

				){
					error_log(
						__METHOD__.': '.
						'unauthorizated - loggedUser needed!'
					);
					
					$controller = new unauthorizated();
				}

				$this->checkModuleAcls($request);
				
				$this->checkLicenseConstraints($request);

				$modelAndView = $this->runController($controller, $request, $this->responseFormat);
			}
			// Если запрещенно
			catch ( AclDeniedException $e ){
				
				error_log(
					__METHOD__.': '.
					'AclDeniedException - '.$e->__toString()
				);
				
				$controller = new forbidden();
				$modelAndView = $this->runController($controller, $request, $this->responseFormat);
			}
			// Если запрещенно по лицензии
			catch (LicenseDeniedException $e){
				error_log(
					__METHOD__.': '.
					'LicenseDeniedException - '.$e->__toString()
				);
				
				$controller = new licenseForbidden();
				$modelAndView = $this->runController($controller, $request, $this->responseFormat);
			}
			// Если баланс отрицательный
			catch (BalanceDeniedException $e) {
				error_log(
					__METHOD__.': '.
					'LicenseDeniedException - '.$e->__toString()
				);
				
				$controller = new balanceLimitExceeded();
				$modelAndView = $this->runController($controller, $request, $this->responseFormat);
			}

			$controllerName = get_class($controller);

			$view 	= $modelAndView->getView();
			$model 	= $modelAndView->getModel();
						
			if(!$view)
				$view = $this->responseFormat->toView($request);


			if (!$view)
				$view = $controllerName;
			elseif (is_string($view)) {
				if ($view == 'error')
					$view = new RedirectView($prefix);
				if (strpos($view, 'redirect:') !== false) {
					list(, $c) = explode(':', $view, 2);

					$view = new RedirectView(PATH_WEB.'?area='.$c);
				}
			}
			elseif ($view instanceof RedirectToView)
				$view->setPrefix($prefix);

			if (!$view instanceof View) {
				$viewName = $view;

				$viewResolver =
					MultiPrefixPhpViewResolver::create()->
					setViewClassName('SimplePhpView');

					foreach ( $this->templatePaths as $path )
						$viewResolver->addPrefix( $path );

					$viewResolver->addPrefix(PATH_TEMPLATES_COMMON);

				$view = $viewResolver->resolveViewName($viewName);

			}

			if (!$view instanceof RedirectView) {
				$model->
					set('selfUrl', PATH_WEB.'?c='.$controllerName)->
					set('controllerName', $controllerName );
			}

			if( !$request->hasAttachedVar('loggedUser') ){
				/*
				 * Если логино зависимый контроллер, то необходимо всегда возвращать
				 * статусы об необходимости авторизации помимо прочего
				 */
				if(
					$controller instanceof IDependentLoggedUser
				){
					$status = AjaxStatusResponse::create();
					$status->setDescription( _('Пользователь не авторизован в системе!') );
					$status->setStatus( AjaxStatusType::unauthrized() );

					$model->set('status', $status);
				}
			}

			// если у нас формат, который резолвиться - запихиваем в него статик данные
			if( $this->responseFormat->isResorveable() ) {
				if(
					$request->hasAttachedVar('loggedUser')
				){
					$model->set('loggedUser', $request->getAttachedVar('loggedUser') );
				}


				// $controllerName
				if(
					isset( $controllerName )
				) {
					$model->set('controllerName', $controllerName );
				}

			}


			if(
				!$this->statusSilent &&
				!$this->responseFormat->isResorveable() &&
				!$model->has('status')
			){
				throw new WrongStateException(
					__METHOD__.': '.
					_('You mus specifie "status" in response mav!')
				);
			}

			$view->render($model);

			return /* void */;
		}
		
		/**
		 * @param HttpRequest $request
		 * @throws LicenseDeniedException
		 */
		protected function checkLicenseConstraints(HttpRequest $request)
		{
			
			if(
				(
					$request->hasAttachedVar('loggedEmployee')
				) &&
				(
					$module = $this->module
				)
			){
				$employee = $request->getAttachedVar('loggedEmployee');
				//$employee = Employee::create();
				
				/*
				 * Если он директор ?
				 * If hi is a god ?
				 */ 
				if(
					$employee->getCompany()->getDirector()->getId() == $employee->getId()
				)
					return;
				
				$billCompany = $employee->getCompany()->getBillCompany();
				
				$license = 
					BillCompanyLicense::dao()->getByCompanyAndModule($billCompany, $module->getBillCompanyModule(), Cache::EXPIRES_MINIMUM);
				
				$module->assert($license, $employee);
				
				ModuleActivity::dao()->updateLastActivityDate($module, $employee, Cache::EXPIRES_MINIMUM);
				
			}
			
			
			
		}
		
		protected function checkModuleAcls(HttpRequest $request)
		{
			// Проверяем доступность модуля
			if( $request->hasAttachedVar('loggedEmployee') )
			{
				$employee = $request->getAttachedVar('loggedEmployee');
				
				if(
					$this->checkModulesAcl
				){
					$tmpModule = ModuleType::typeCrm();
					$list = $tmpModule->getObjectList();
					
					foreach ( $list  as $currModule )
					{
						try{
							BillModuleChecker::create()->setEmployee(
								$employee
							)->setModule(
								$currModule
							)->run();
						} catch (Exception $e) {
							error_log(__METHOD__.': '.$e->__toString() );
						}
						
					}
				}
				
				if( $this->module )
				{					
					try{
						Acl::assert($employee, AclActionType::read(), $this->module);
					} catch (AclDeniedException $e){
						
						$module = $this->module;
						$license = BillCompanyLicense::dao()->getByCompanyAndModule(
							$employee->getCompany()->getBillCompany(),
							$module->getBillCompanyModule(),
							Cache::EXPIRES_MEDIUM
						);
						
						if(
							!$license->isAvailable()
						){
							throw new BalanceDeniedException();
						}
						
						throw $e;
					}
					
				}
					
			}
		}

		/**
		 * @param HttpRequest $request
		 */
		protected function rewrite(HttpRequest $request)
		{
			/*
			 * Весь реврайт просиходит тут :-)
			 * Вот и усе
			 */
			if(
				$this->routerRewrite
			){
				$this->routerRewrite->route($request);

				$request->setGet(
					array_merge(
						$request->getGet(),
						$request->getAttached()
					)
				);

			}

			return /* void */;
		}

		/**
		 * @param HttpRequest $request
		 */
		protected function tokenize(HttpRequest $request)
		{
			$form = Form::create()->add(
				Primitive::uuidIdentifier('utoken')
					->of('AuthToken')
					->required()
			);
			
			$form->import( $request->getGet() );
			$form->importMore( $request->getPost() );

			if( !$form->getErrors() )
			{
				try{

					$token = $form->getValue('utoken');
					//$token = AuthToken::create();
					
					if(
						!$token->isValid()
					){
						throw new ObjectNotFoundException();
					}
					
					AuthToken::dao()->update($token);
					
					$dao = $this->getUserDAO();
					$object = $dao->getById( $token->getUserId() );
					
					
					$request->setAttachedVar(
						'loggedUser',
						$object
					);
					
					// Обновляем дату последней активности
					$dao->updateLastActivity( $object );

				} catch (ObjectNotFoundException $e) {/**/}

			}

			return /* void */;
		}
		
		/**
		 * @return UserDAO
		 */
		protected function getUserDAO()
		{
			return call_user_func(
				array( $this->userClassName, 'dao' )
			);
		}

		/**
		 * @param HttpRequest $request
		 */
		protected function attacheLoggedEmployee(HttpRequest $request)
		{
			if(
				$request->hasAttachedVar('loggedCompany') &&
				(
					( $loggedCompany = $request->getAttachedVar('loggedCompany') ) instanceof Company
				) &&
				$request->hasAttachedVar('loggedUser') &&
				(
					( $loggedUser = $request->getAttachedVar('loggedUser') ) instanceof User
				)

			){
				
				try{
					$dao = $this->getEmployeeDAO();
					$employee = $dao->getByUserAndCompany($loggedUser, $loggedCompany);
					
					
					// Обновляем дату последней активности.
					$dao->updateLastActivity($employee);

					$request->setAttachedVar('loggedEmployee', $employee);
				}catch (ObjectNotFoundException $e) {
					error_log(
						__METHOD__.': ObjectNotFoundException = '. $e->__toString()
					);
				}
			}

			return /* void */;
		}
		
		/**
		 * @return EmployeeDAO
		 */
		protected function getEmployeeDAO()
		{
			return call_user_func(
				array( $this->employeeClassName, 'dao' )
			);
		}

		/**
		 * @return CustomerDAO
		 */
		protected function getCustomerDAO()
		{
			return call_user_func(
				array( $this->customerClassName, 'dao' )
			);
		}

		/**
		 * @param HttpRequest $request
		 */
		protected function attacheLoggedUserOrCompany(HttpRequest $request)
		{
			if(
				isset( $_SERVER ) &&
				(
					$alias = SecurityManager::me()->getAlias( $_SERVER )
				)
			){

				try {

					$company =  call_user_func(
						array( $this->companyClassName, 'dao' )
					)->getByAlias($alias);

					$request->setAttachedVar('loggedCompany', $company);

				} catch ( ObjectNotFoundException $e) {/**/}

			}

			return /* void */;
		}


		protected function checkAliasConstraints(HttpRequest $request)
		{

			if( !isset( $_SERVER ) )
				return /*void*/;

			$aliases = array();

			if(
				$request->hasAttachedVar('loggedUser')
			) {
				$aliases[] = $request->getAttachedVar('loggedUser')->getAlias();
			}

			if(
				$request->hasAttachedVar('loggedCompany')
			) {
				$aliases[] = $request->getAttachedVar('loggedCompany')->getAlias();

			}


		}

	}
