<?php
/**
 * Class Application_Plugin_AccessCheck
 *
 * ѕолагин дл€ реализации прав доступа ролей к ресурсам
 */
class Application_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
{

	private $_acl;

	public function __construct()
	{
		// —оздаЄм объект Zend_Acl
		$this->_acl = new Zend_Acl();

		// ƒобавл€ем ресурсы нашего сайта,
		// другими словами указываем контроллеры и действи€

		// указываем, что у нас есть ресурс index
		$this->_acl->addResource('index');

		// ресурс add €вл€етс€ потомком ресурса index
		$this->_acl->addResource('add', 'index');

		// ресурс edit €вл€етс€ потомком ресурса index
		$this->_acl->addResource('edit', 'index');

		// ресурс delete €вл€етс€ потомком ресурса index
		$this->_acl->addResource('delete', 'index');

		// указываем, что у нас есть ресурс error
		$this->_acl->addResource('error');

		// указываем, что у нас есть ресурс auth
		$this->_acl->addResource('auth');

		// ресурс login €вл€етс€ потомком ресурса auth
		$this->_acl->addResource('login', 'auth');

		// ресурс logout €вл€етс€ потомком ресурса auth
		$this->_acl->addResource('logout', 'auth');

		$this->_acl->addResource('players');

		// далее переходим к созданию ролей, которых у нас 2:
		// гость (неавторизированный пользователь)
		$this->_acl->addRole('guest');

		// администратор, который наследует доступ от гост€
		$this->_acl->addRole('admin', 'guest');

		// разрешаем гостю просматривать ресурс index
		$this->_acl->allow('guest', 'players', array('index', 'add'));

		// разрешаем гостю просматривать ресурс auth и его подресурсы
		$this->_acl->allow('guest', 'auth', array('index', 'login', 'logout'));

		// даЄм администратору доступ к всем ресурсам
		$this->_acl->allow('admin');

		// разрешаем администратору просматривать страницу ошибок
		$this->_acl->allow('admin', 'error');

	}


	public function preDispatch( Zend_Controller_Request_Abstract $request ) {
		// получаем им€ текущего ресурса
		$resource 	= $request->getControllerName();

		// получаем им€ action
		$action 		= $request->getActionName();

		// получаем доступ к хранилищу данных Zend,
		// и достаЄм роль пользовател€
		$identity = Zend_Auth::getInstance()->getStorage()->read();

		// если в хранилище ничего нет, то значит мы имеем дело с гостем
		$role = ! empty( $identity->status ) ? $identity->status : 'guest';

		// если пользователь не допущен до данного ресурса,
		// то отсылаем его на страницу авторизации
		if ( ! $this->_acl->isAllowed( $role, $resource, $action ) ) {
			$request->setControllerName('error')->setActionName('deny');
		}
	}


}
