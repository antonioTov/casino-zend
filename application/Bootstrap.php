<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{


	/**
	 * Инициализация плагина авторизации
	 */
	public function _initAuth()
	{

		if ( ! Zend_Auth::getInstance()->getIdentity() ) {
			$this->_register( new Application_Plugin_AuthCheck() );
		}

	}


	/**
	 * Инициализация плагина прав доступа
	 */
	public function _initAcl()
	{
		$this->_register( new Application_Plugin_AccessCheck() );
	}


	/**
	 * Инициализация кеша метадаты таблиц
	 */
	public function _initMetaDataCache()
	{
		$frontendOptions = array (
			'automatic_serialization' => true,
			'lifetime' => null,
			);
		$backendOptions = array ('cache_dir' =>  './tmp/db-tables-metadata');
		$cache = Zend_Cache::factory(
			'Core',
			'File',
			$frontendOptions,
			$backendOptions
		);

		Zend_Db_Table::setDefaultMetadataCache( $cache );
	}



	/**
	 * Регистрация плагинов
	 *
	 * @param Zend_Controller_Plugin_Abstract $plugin
	 */
	private function _register( Zend_Controller_Plugin_Abstract $plugin )
	{
		Zend_Controller_Front::getInstance()
			->registerPlugin( $plugin );
	}

}

