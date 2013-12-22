<?php

class Application_Model_DbTable_Administrators extends Zend_Db_Table_Abstract
{

    protected $_name = 'administrators';


	public function getByName( $name )
	{
		return $this->fetchRow( $this->select()->where('login = ?', (string) $name ) );
	}

}

