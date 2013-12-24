<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{


	// Инициализация плагина авторизации
	public function _initAuth()
	{
		Zend_Auth::getInstance()->getStorage()->read();

		$this->_register( new Application_Plugin_AuthCheck() );
	}


	// Инициализация плагина прав доступа
	public function _initAcl()
	{
		$this->_register( new Application_Plugin_AccessCheck() );
	}


	// Регистрация плагинов
	private function _register( Zend_Controller_Plugin_Abstract $plugin )
	{
		Zend_Controller_Front::getInstance()
			->registerPlugin( $plugin );
	}

}

