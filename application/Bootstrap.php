<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function _initAuth()
	{
		$auth = Zend_Auth::getInstance();
		$data = $auth->getStorage()->read();

		if ( ! isset( $data->status ) )
		{

//			$storage_data = new stdClass();
//			$storage_data->status = 'guest';
//			$auth->getStorage()->write($storage_data);
			/*
			$frontController = Zend_Controller_Front::getInstance();
			$response = new Zend_Controller_Response_Http();
			$response->setRedirect('/auth');
			$frontController->setResponse($response);
			*/
		}
	}

}

