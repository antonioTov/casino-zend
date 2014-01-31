<?php

class Application_Form_Login extends Zend_Form
{

   public function init()
    {
		$this->setMethod('post');

		$login = new Zend_Form_Element_Text('login');
		$login->setLabel('�����')
			->setRequired(true)
			->setAttrib('class', 'form-control')
			->addFilter('StripTags')
			->addFilter('StringTrim')
//			->addValidators(array(
//				array('NotEmpty', true, array('messages' => array(
//					'isEmpty' => '����� �� ����� ���� ������!',
//				)))))
			->setDecorators(array(
				'ViewHelper',
//				'Errors',
				array(array('td' => 'HtmlTag'), array('tag' => 'td')),
				array('Label'),
			));


		$pass = new Zend_Form_Element_Password('pass');
		$pass->setLabel('������')
			->setRequired(true)
			->setAttrib('class', 'form-control')
			->addFilter('StripTags')
			->addFilter('StringTrim')
//			->addValidators(array(
//				array('NotEmpty', true, array('messages' => array(
//					'isEmpty' => '������ �� ����� ���� ������!',
//				)))))
			->setDecorators(array(
				'ViewHelper',
//				'Errors',
				array(array('td' => 'HtmlTag'), array('tag' => 'td')),
				array('Label'),
			));


		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('class', 'btn btn-info')
			->setLabel('�����')
			->setDecorators(array(
				'ViewHelper',
				'Errors'));

		$this->setDecorators(array(
			'FormElements',
			array('HtmlTag', array('tag' => 'div', 'class' => 'login-wrp')),
			'Form',
		));

		$this->addElements( array( $login, $pass, $submit ) );

    }



}

