<?php
/**
 * zelenov.mikhail@gmail.com
 */
	class StaticSecurityManager extends SecurityManager {

		// Название hash ключа идинтификатора пользователя
		const COOKIE_USER_IDETIFICATION				=	'utoken';

		const COOKIE_COMPANY_IDETIFICATION			=	'c';

		// Название hash ключа ip
		const COOKIE_USER_IDETIFICATION_IP			=	'extensip';

		// Секретный ключь :-)
		const CRYPT_SALT							=	'bestofthebestofthebestsalt';

		/** @var $_company Company */
		protected $_company;

		/**
		 * @return SecurityManager
		 */
		static public function me() {
			return Singleton::getInstance(__CLASS__);
		}

		protected function __construct() {
			parent::__construct();
		}

		/**
		 * @param AuthToken $utoken
		 * @param boolean $isRemember
		 * @return void
		 */
		public function setToken(AuthToken $utoken, $isRemember = true)
		{
			//Записываем куки
			return $this->setCookiesToken($utoken, $isRemember);
		}

		/**
		 * Разлогиниваем пользователя
		 *
		 * @return void
		 */
		public function logout() {
			$this->unsetCookies();


			return /*void*/;
		}

	/**
		 * Обнуляем авторизационные данные!
		 *
		 * @return void
		 */
		private function unsetCookies() {

			$cookie = Cookie::create(self::COOKIE_USER_IDETIFICATION);
			$cookie->setPath('/');
			CookieUtils::unsetCookie($cookie);

			if($this->isSecureLogin() ) {
				$cookie = Cookie::create(self::COOKIE_USER_IDETIFICATION_IP);
				$cookie->setPath('/');
				CookieUtils::unsetCookie($cookie);
			}

			return /*void*/;
		}

		/**
		 * Возвращает id из сесси пользователя
		 *
		 * @return string || null
		 */
		private function getUserIdFromCookie() {
			$utokenId = CookieUtils::getById(self::COOKIE_USER_IDETIFICATION);
			$userId = null;

			if(
				Assert::checkUniversalUniqueIdentifier( $utokenId )
			){

				try{

					$utoken = AuthToken::dao()->getById($utokenId);

					if(
						$utoken->isValid()
					){
						$userId = $utoken->getUserId();
					}

				} catch ( ObjectNotFoundException $e ){/**/}

			}


			return $userId;
		}


		/**
		 * Возвращает utoken, если есть
		 *
		 * @return utoken || null
		 */
		public function getUtoken() {
			$utokenId = CookieUtils::getById(self::COOKIE_USER_IDETIFICATION);
			if(
				Assert::checkUniversalUniqueIdentifier( $utokenId )
			){

				try{

					$utoken = AuthToken::dao()->getById($utokenId);
					if(
						$utoken->isValid()
					){
						return $utoken;
					}

				} catch ( ObjectNotFoundException $e ){/**/}
			}
			return null;
		}

		/**
		 * @return Company
		 */
		public function getCompanyByAlias()
		{
			try {

				Assert::isNotNull($_SERVER);
				$alias = $this->getAlias($_SERVER);
				Assert::isNotNull($alias);

				$company = Company::dao()->getByAlias($alias);

				return $company;
			}
			catch (ObjectNotFoundException $e) {/**/}
			catch (WrongArgumentException $e) {/**/}

			return null;
		}

		/**
		 * @param AuthToken $utoken
		 * @param boolean $isRemember
		 * @return void
		 */
		private function setCookiesToken(AuthToken $utoken, $isRemember=true) {

			$expire = 0; // До закрытия браузера

			if($isRemember) {
				$expire=2 * 604800; // Неделя * 2 = 2 недели :-)
			}

			// Сохраняем id в куки
			$cookieUserId=Cookie::create(self::COOKIE_USER_IDETIFICATION);
			$cookieUserId->setDomain(COOKIE_DOMAIN);
			$cookieUserId->setPath('/');
			$cookieUserId->setValue( $utoken->getId() );
			$cookieUserId->setMaxAge($expire);
			CookieUtils::setCookie($cookieUserId);


			if($this->isSecureLogin() ) {
				// Сохраняем ip в куки
				$cookieUserIp=Cookie::create(self::COOKIE_USER_IDETIFICATION_IP);
				$cookieUserIp->setDomain(COOKIE_DOMAIN);
				$cookieUserIp->setPath('/');
				$cookieUserIp->setValue( self::encrypt( $this->getIp() ) );
				$cookieUserIp->setMaxAge($expire);
				CookieUtils::setCookie($cookieUserIp);
			}

			return /*void*/;
		}

		public function setCookiesCompany(Company $company, $isRemember = true) {
			$expire = 0; // До закрытия браузера

			if($isRemember) {
				$expire=2 * 604800; // Неделя * 2 = 2 недели :-)
			}

			// Сохраняем id в куки
			$cookieUserId=Cookie::create(self::COOKIE_COMPANY_IDETIFICATION);
			$cookieUserId->setDomain(COOKIE_DOMAIN);
			$cookieUserId->setPath('/');
			$cookieUserId->setValue( $company->getId() );
			$cookieUserId->setMaxAge($expire);
			CookieUtils::setCookie($cookieUserId);
		}

		/**
		 * Проверяем находимся ли мы в домене компании
		 * @return boolean
		 */
		public function isInDomenCompany() {
			//проверяем инициализирован ли кэш компании $this->_company
			//если нет то инициализируем
			if (is_null($this->_company)) {
				$this->_company = $this->getCompany();
			}

			//если залогинен, и есть alias компании и компании совпадают,
			//то да мы в домене компании
			$company = $this->getCompanyByAlias();

			if (!is_null($this->_company) &&
				$this->_company instanceof Company &&
				$company &&
				$this->_company->getAlias() == $company->getAlias()
				) {
				return true;
			}
			return false;
		}

		/**
		 * Берём компанию из кук, или первую попавшуюся
		 * @return Company|false
		 */
		public function getCompanyFromCookiesOrFirstAvailable() {
			/** @var $user User */
			$user = $this->getUtoken()->getUser();
			$company = $this->getCompanyFromCookies();

			if ($company) {
				try {
					$employee = Employee::dao()->getByUserAndCompany($user, $company);
					if ($employee->getStatus()->getId() == UserStatus::DISMISSED ||
						$employee->getStatus()->getId() == UserStatus::NEW_USER) {
						$company = false;
					}
				} catch (ObjectNotFoundException $e) {
					$company = false;
				}
			}

			if (!$company) {
				//берём первую попавшуюся нормальную компанию
				$companies = $user->getAvalilableCompanies()
							->add(Expression::notEq('employees.status', UserStatus::NEW_USER))
							->add(Expression::notEq('employees.status', UserStatus::DISMISSED))
							->setLimit(1)->getList();
				$company = array_shift( $companies );
			}

			// пробуем найти компанию через кастомера
			if (!$company) {
				$criteria =
					Criteria::create( Customer::dao() )
						->add(
							Expression::andBlock(
								Expression::eq(
									'user.id',
									$user->getId()
								),
								Expression::eq(
									'status',
									CustomerStatus::CUSTOMER_ACTIVE
								)
							)
						)
						->addOrder(
							OrderBy::create('registerDate')->desc()
						)
						->setLimit(1);
				$list = $criteria->getList();
				if( count($list)>0 ) {
					// получаем костюмера
					/** @var $customer Customer */
					$customer = array_shift( $list );
					// получаем компанию
					$company = $customer->getCompany();
				}
			}

			// ни одной компании не нашли
			if (!$company) {
				throw new WrongStateException();
			}

			return $company;
		}

		/**
		 * Получаем компанию, в которой по умолчанию авторизовываемся.
		 * Определяется компания, в начале выполнения скрипта.
		 * Потом берётся из "кэша".
		 * @return Company
		 */
		public function getCompany() {

			//$this->_company может быть и false!
			if (!is_null($this->_company)) {
				return $this->_company;
			}

			$user = $this->getUtoken()->getUser();

			//Если есть домен компании, то получаем компанию оттуда.
			if ($company = $this->getCompanyByAlias()) {
				try {
					//обратим внимание, что объект employee берётся из Employee
					$employee = Employee::dao()->getByUserAndCompany($user, $company);

					//@todo: сомневаюсь в этой проверке
					if ($employee->getStatus()->getId() == UserStatus::DISMISSED ||
						$employee->getStatus()->getId() == UserStatus::NEW_USER) {
						//нет прав на просмотр этой компании, значит берём другую компанию
						$company = false;
					}
				} catch (ObjectNotFoundException $e) {
					//нет прав на просмотр этой компании, значит берём другую компанию
					$company = false;
				}
			}

			if (!$company) {
				//Берём компанию из кук, или первую попавшуюся
				$company = $this->getCompanyFromCookiesOrFirstAvailable();
			}

			$this->_company = false;

			if ($company) {
				$this->setCookiesCompany($company);
				$this->_company = $company;
			}

			return $company;
		}

		/**
		 * Берём компанию из кук
		 * @return Ambigous <boolean, Company>
		 */
		public function getCompanyFromCookies() {
			$companyId = CookieUtils::getById(self::COOKIE_COMPANY_IDETIFICATION);

			if(
				Assert::checkUniversalUniqueIdentifier( $companyId )
			){

				try{

					$company = Company::dao()->getById($companyId);
					return $company;
				} catch ( ObjectNotFoundException $e ){/**/}

			}
			return false;
		}
	}
