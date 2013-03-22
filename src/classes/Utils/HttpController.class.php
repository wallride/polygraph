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


	final class HttpController extends Singleton implements Instantiatable
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
		 * Молчать при отсутствии статуса
		 * @var boolean
		 */
		protected $statusSilent		= true;



		/**
		 * Запрос
		 * @var HttpRequest
		 */
		protected $request			= null;

		/**
		 * Включение XHProfiling-а
		 * @var boolean
		 */
		protected $xhprofiling		= null;

		/**
		 * @return HttpController
		 */
		public static function me()
		{
			return self::getInstance(__CLASS__);
		}



		/**
		 * @param ModuleType $module
		 * @return HttpController
		 */
		public function setModule(ModuleType $module)
		{
			$this->module = $module;

			return $this;
		}

		/**
		 * @param boolean $boolean
		 * @return HttpController
		 */
		public function setXHProfiling($boolean)
		{
			$this->xhprofiling = (true == $boolean);

			return $this;
		}

		/**
		 * @param boolean $boolean
		 * @return HttpController
		 */
		public function setStatusSilent($boolean)
		{
			$this->statusSilent = (true == $boolean);

			return $this;
		}

		/**
		 * @param boolean $boolean
		 * @return HttpController
		 */
		public function setCheckModulesAcl($boolean)
		{
			$this->checkModulesAcl = (true == $boolean);

			return $this;
		}

		/**
		 * @param array $paths
		 * @return HttpController
		 */
		public function setTemplatePaths(/* array */$paths)
		{
			Assert::isArray($paths);

			$this->templatePaths = $paths;

			return $this;
		}

		/**
		 * @param string $className
		 * @return HttpController
		 */
		public function setUserClassName($className)
		{
			Assert::classExists($className);
			$this->userClassName = $className;

			return $this;
		}

		/**
		 * @param string $className
		 * @return HttpController
		 */
		public function setCompanyClassName($className)
		{
			Assert::classExists($className);
			$this->companyClassName = $className;

			return $this;
		}

		/**
		 * @param string $className
		 * @return HttpController
		 */
		public function setEmployeeClassName($className)
		{
			Assert::classExists($className);
			$this->employeeClassName = $className;

			return $this;
		}

		/**
		 * @param string $className
		 * @return HttpController
		 */
		public function setCustomerClassName($className)
		{
			Assert::classExists($className);
			$this->customerClassName = $className;

			return $this;
		}


		/**
		 * @param array $paths
		 * @return HttpController
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
		 * Ставим RouterRewrite
		 * @param RouterRewrite $rewrite
		 * @return HttpController
		 */
		public function setRouterRewrite(RouterRewrite $rewrite)
		{
			$this->routerRewrite = $rewrite;

			return $this;
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
		 * Вся инициализация тут
		 * @param HttpRequest $request
		 */
		protected function init(HttpRequest $request)
		{
			$this->request = $request;

			$this->initResponseFormat($this->request);

			$this->initRewrite($this->request);

			$this->initUtoken($this->request);

		}

		/**
		 * @param HttpRequest $request
		 * @return HttpController
		 */
		protected function initResponseFormat(HttpRequest $request)
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
				$this->responseFormat = ResponseFormatType::wrap(
					$form->getChoiceValue('fmt')
				);

			} else{
				// Default response format
				$this->responseFormat = ResponseFormatType::typeXhtml();
			}

			return $this;
		}


		/**
		 * @param HttpRequest $request
		 * @return HttpController
		 */
		protected function initRewrite(HttpRequest $request)
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

			return $this;
		}


		/**
		 * @param HttpRequest $request
		 * @throws ObjectNotFoundException
		 * @return HttpController
		 */
		protected function initUtoken(HttpRequest $request)
		{
			$form = Form::create()->add(
				Primitive::uuidIdentifier('utoken')->
					of('AuthToken')->
					required()
			);

                        
                        if ($request->hasCookieVar('utoken'))
                            $form->importValue('utoken', $request->getCookieVar('utoken'));
			$form->importMore( $request->getGet() );
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
//					$dao->updateLastActivity( $object );

				} catch (ObjectNotFoundException $e) {/*_*/}

			}

			return $this;
		}

		/**
		 * @param HttpRequest $request
		 * @throws WrongArgumentException
		 * @return Ambigous <string, multitype:>
		 */
		protected function getControllerName(HttpRequest $request)
		{
			$controllerName =
				defined('MAIN_CONTROLLER')
					?  MAIN_CONTROLLER :
					'main';

			if(
                            $request->hasGetVar('area') &&
                            ClassUtils::isClassName($request->getGetVar('area') )
			){
                            $controllerName = $request->getGetVar('area');

			}elseif(
                            $request->hasPostVar('area') &&
                            ClassUtils::isClassName($request->getPostVar('area') )
			){
				$controllerName = $request->getPostVar('area');
			}

			return $controllerName;
		}

		/**
		 * Создаем контроллер
		 * @param string $controllerName
		 * @return classNotFoundController|unauthorizated|forbidden|Ambigous <IDependentLoggedUser, IDependentLoggedCompany, unknown>
		 */
		protected function makeController($controllerName)
		{
			try{
				Assert::classExists($controllerName);
			} catch (WrongArgumentException $e) {
				return new pageController();
			}
			$controller = new $controllerName;
			$request = $this->request;

			if(
				(
					(
						$controller instanceof IDependentLoggedUser

					) &&
					!(
						$request->hasAttachedVar('loggedUser') &&
						$request->getAttachedVar('loggedUser') instanceof User
					)
				)

			){
				error_log(
					__METHOD__.': '.
					'unauthorized - loggedUser needed!'
				);

				return new unauthorizated();
			}


			return $controller;

		}

		/**
		 * Стартуем контроллер
		 * @param string $controllerName
		 * @return ModelAndView
		 */
		protected function runController($controllerName)
		{
			$class = $this->makeController($controllerName);
/*
			$request = $this->request;
			$interface = $this->responseFormat->toInterface();

//                        var_dump($class, $this->responseFormat->toInterfaceMethod());
			if($class instanceof $interface) {
                            $mav = call_user_func(
                                array($class, $this->responseFormat->toInterfaceMethod() ),
                                $request
                            );
			} else {
                            $mav = $class->handleRequest($request);
			}

			$view 	= $mav->getView();
			$model 	= $mav->getModel();

			$template = null;

			if(is_string($view))
				$template = $view;

			if(!$view || is_string($view))
			{
				$responseView = $this->responseFormat->toView($request);
				if( $responseView )
					$view = $responseView;
			}


			if(
				$this->responseFormat->isResorveable() ||
				$this->responseFormat->isCtppable()
			) {

				if (!$view instanceof View) {
					$viewName = ($view)
									? $view
									: $controllerName;

					$viewResolver =
						MultiPrefixPhpViewResolver::create()->
						setViewClassName('SimplePhpView');

						foreach ( $this->templatePaths as $path )
							$viewResolver->addPrefix( $path );

//					$view = $viewResolver->resolveViewName($viewName);

				}

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
*/
			/*
			 * Init current time
			if(
				!$this->responseFormat->isResorveable()
			){
				$this->injectTime($model);
			}
			 */

			/*
			 * Ctpp X-Template
			if(
				$this->responseFormat->isCtppable() &&
				!HeaderUtils::hasHeader(CtppView::HEADER_NAME)
			)
			{
				if(
					!$template ||
					!is_string($template)
				)
					$template = $controllerName;

				 HeaderUtils::sendCustomHeader(
					CtppView::HEADER_NAME.': '.$template.'.'.CtppView::CEXTENSION
				);
			}
			 */

                        return $class;
			return /* void */;

		}

		/**
		 * @param Model $model
		 * @return HttpController
		 */
		protected function injectTime(Model $model)
		{
			if( $model->has('time') )
			{
				$time = $model->get('time');
				if(
					is_array( $time ) &&
					!isset( $time['now'] )
				)
					$time['now'] = Timestamp::makeNow()->toString();
			} else {
				$model->set('time', array( 'now' => Timestamp::makeNow()->toString() ) );
			}

			return $this;
		}

		public function run(HttpRequest $request)
		{
			$this->init($request);

			$controllerName = $this->getControllerName($request);

			try{

				return $this->runController($controllerName);

			}
			catch (Exception $e) {

				if(
					!$this->responseFormat->isResorveable()
				){
					LoggerUtils::logException($e);

					$request->setAttachedVar('errorMessage', $e->__toString() );
//                                        var_dump($e->getTraceAsString());
//					$this->runController('faild');

					return /* void */;
				}

				throw $e;
			}

		}

	}