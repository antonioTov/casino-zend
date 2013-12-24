<?php
class Application_Plugin_AuthCheck extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch( Zend_Controller_Request_Abstract $request )
	{
		if ( ! Zend_Auth::getInstance()->getIdentity() )
		{
			$request->setControllerName('auth')->setActionName('index');
		}

	}

}
