<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{


	// ������������� ������� �����������
	public function _initAuth()
	{
		Zend_Auth::getInstance()->getStorage()->read();

		$this->_register( new Application_Plugin_AuthCheck() );
	}


	// ������������� ������� ���� �������
	public function _initAcl()
	{
		$this->_register( new Application_Plugin_AccessCheck() );
	}


	// ����������� ��������
	private function _register( Zend_Controller_Plugin_Abstract $plugin )
	{
		Zend_Controller_Front::getInstance()
			->registerPlugin( $plugin );
	}

}

