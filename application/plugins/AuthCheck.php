<?php
class Application_Plugin_AuthCheck extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch( Zend_Controller_Request_Abstract $request )
	{
			$request->setControllerName('auth')->setActionName('index');
	}

}
