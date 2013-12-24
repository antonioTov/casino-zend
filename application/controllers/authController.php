<?php

class AuthController extends Zend_Controller_Action
{

	public function indexAction()
	{

		if ( Zend_Auth::getInstance()->hasIdentity() ) {
			$this->redirect('/');
		}

		$form = new Application_Form_Login();
		$this->view->form = $form;

		if ( $this->getRequest()->getPost() )
		{
			$formData = $this->getRequest()->getPost();
			if ( $form->isValid( $formData ) )
			{
				$bootstrap 	= $this->getInvokeArg('bootstrap');
				$auth 			= Zend_Auth::getInstance();
				$adapter 		= $bootstrap->getPluginResource('db')->getDbAdapter();
				$adminModel	= new Application_Model_DbTable_Administrators();
				$tableAdmin 	= $adminModel->info('name');
				$authAdapter	= new Zend_Auth_Adapter_DbTable(
					$adapter,
					$tableAdmin,
					'login',
					'pass',
					'MD5(?) AND active = 1'
				);

				$authAdapter->setIdentity( $form->login->getValue() );
				$authAdapter->setCredential( $form->pass->getValue() );
				$result = $auth->authenticate( $authAdapter );

				if ( $result->isValid() ) {
					$storage = $auth->getStorage();
					$storage_data = $authAdapter->getResultRowObject(
						null,
						array('activate', 'password', 'enabled'));
					$storage_data->status = 'admin';
					$storage->write( $storage_data );

					$this->redirect('/');
				}
				else {
					$this->_helper->FlashMessenger('Неправильный логин или пароль!');
				}

			}
			else {
				$this->_helper->FlashMessenger('Неправильный логин или пароль!');
			}

		}

		$this->view->message = $this->getHelper('FlashMessenger')->getCurrentMessages();
	}


	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector('index'); // back to login page
	}
}