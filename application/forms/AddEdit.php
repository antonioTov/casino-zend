<?php
class Application_Form_AddEdit extends Zend_Form
{

	public function init()
	{

		$emptyMsg 	=  'Поле не может быть пустым!';
		$errorEmail 	=  'Введите корректный E-mail';
		$busyName 	=  'Логин уже занят';

		$this->setMethod('post');

		// Username
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel('Username')
			->setRequired(true)
			->setAttrib('class', 'f')
			->setAttrib('autocomplete', 'off')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array(
					'messages' => array(
						'isEmpty' => $emptyMsg
					)
				)),
				array('Db_NoRecordExists', false, array(
					'table' 	=> 'players',
					'field' 	=> 'username',
					'messages' => array(
						'recordFound' => $busyName
					)
				))
			));

		// First name
		$firstName = new Zend_Form_Element_Text('first_name');
		$firstName->setRequired(true)
			->setAttrib('class', 'f')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg
				)))) );


		// Last name
		$lastName = new Zend_Form_Element_Text('last_name');
		$lastName->setLabel('Last name')
			->setRequired(true)
			->setAttrib('class', 'f')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg
				)))) );


		// Date of birth
		$birthDate = new Zend_Form_Element_Text('birth_date');
		$birthDate->setLabel('Date of birth')
			->setRequired(true)
			->setAttrib('class', 'f')
			->setAttrib('id', 'datepicker')
			->setAttrib('autocomplete', 'off')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addValidators( array(
				array('NotEmpty', true, array('messages' => array(
					'isEmpty' => $emptyMsg
				)))) );


		// Email
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('E-mail')
			->setRequired(true)
			->setAttrib('class', 'f')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper', 'Errors') )
			->addErrorMessage($errorEmail)
			->addValidator('EmailAddress',  true  );



		// -- hidden -- //

		// Admin ID
		$adminID = new Zend_Form_Element_Hidden( 'admin_id' );
		$adminID->setValue( Zend_Auth::getInstance()->getIdentity()->id )
			->setDecorators( array('ViewHelper') );

		// Player ID
		$playerID = new Zend_Form_Element_Hidden( 'player_id' );
		$playerID->setDecorators( array('ViewHelper') );

		// -- hidden end -- //


		$this->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/AddEdit.phtml'))
		));

		$this->addElements( array( $username, $firstName, $lastName, $birthDate, $email, $adminID, $playerID ) );
	}


}