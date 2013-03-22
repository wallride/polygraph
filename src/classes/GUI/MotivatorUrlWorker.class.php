<?php
/**
 * Created by Alexey V. Gorbylev at 03.03.11 14:29
 * email: gorbylev@mymotivator.ru
 */

class MotivatorUrlWorker extends SimpleUrlWorker {

	/**
	 * @var Company
	 */
	protected $company = null;

	/**
	 * @var ModuleType
	 */
	protected $module = null;

	/**
	 * @return MotivatorUrlWorker
	 */
	public static function me()
	{
		return Singleton::getInstance('MotivatorUrlWorker');
	}

	/**
	 * @param Company $company
	 * @return MotivatorUrlWorker
	 */
	public function setCompany(Company $company)
	{
		$this->company = $company;

		return $this;
	}

	/**
	 * @param ModuleType $moduleType
	 * @return MotivatorUrlWorker
	 */
	public function setModule( ModuleType $moduleType ) {
		$this->module = $moduleType;

		return $this;
	}

	/**
	 * @see src/classes/GUI/SimpleUrlWorker::toString()
	 *
	 * @return string
	 */
	public function toString()
	{
		$host = 'http://';
		// если есть компания - добавляем ее УРЛ 3-его уровня
		if( $this->company instanceof Company ) {
			$host .= $this->company->getAlias();
			$host .= '.';
		}
		// добавляем основной домен
		$host .= COOKIE_DOMAIN;
		// если передан модуль - добавляем и его в путь
		if( $this->module instanceof ModuleType ) {
			$host .= '/';
			$host .= $this->module->getWebPath();
		}
		// добиваем слешем на конце
		$host .= '/';
		// устанавливаем хост в воркер
		$this->setHost( $host );

		return parent::toString();
	}


}
