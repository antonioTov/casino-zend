<?php
class Application_Form_Search extends Zend_Form
{

	public function init()
	{

		$this->setMethod('get');
		$this->setAction('/players/search');

		// Username
		$username = new Zend_Form_Element_Text('username');
		$username
			->setAttrib('autocomplete', 'off')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper') );


		// First name
		$firstName = new Zend_Form_Element_Text('first_name');
		$firstName
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper') );



		// Last name
		$lastName = new Zend_Form_Element_Text('last_name');
		$lastName
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper') );


		// Date of birth
		$birthDate = new Zend_Form_Element_Text('birth_date');
		$birthDate
			->setAttrib('id', 'datepicker')
			->setAttrib('autocomplete', 'off')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper') );


		// Email
		$email = new Zend_Form_Element_Text('email');
		$email
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper') );


		// Admin name
		$adminNamel = new Zend_Form_Element_Text('admin_id');
		$adminNamel
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setDecorators( array('ViewHelper') );


		$this->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/Search.phtml'))
		));

		$this->addElements( array( $username, $firstName, $lastName, $birthDate, $email, $adminNamel ) );
	}


}