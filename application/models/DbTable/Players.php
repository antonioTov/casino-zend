<?php

class Application_Model_DbTable_Players extends Zend_Db_Table_Abstract
{

    protected $_name = 'players';


	/**
	 * ���������� ��������� �����������
	 * @var
	 */
	public $count;


	/**
	 * ��������� ������ ���� �������
	 * @param null $condition
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getAll( $condition = null )
	{
		$admin = new Application_Model_DbTable_Administrators();
		$tableAdmin = $admin->info('name');

		if ( $condition === null ) {
			$condition = " 1 ";
		}

		$select = $this->select()->setIntegrityCheck( false )
										->from( array('p' => $this->_name ),
														array('p.id', 'p.username', 'p.first_name', 'p.last_name', 'p.birth_date', 'p.email') )
										->joinLeft( array('a' => $tableAdmin ), 'p.admin_id = a.id',
														array( 'adminLogin' => 'login' ) )
										->where( $condition );

		$rows = $this->fetchAll( $select );
		$this->count = count( $rows );

		return $rows;
	}


	/**
	 * ��������� ������ ������ �� ID
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getPlayer( $id )
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row) {
			throw new Exception("Could not find row $id");
		}
		return $row->toArray();
	}


	/**
	 * ����� ������ �� �����
	 * @param $name
	 * @return bool
	 */
	public function getByName( $name )
	{
		$name = (string) $name;
		$row = $this->fetchRow( $this->select()->where('username = ?' , $name) );
		if (!$row) {
			return false;
		}
		return true;
	}


	/**
	 * ���������� ������
	 * @param $data
	 */
	public function addPlayer( $data )
	{
		$this->insert( $data );
	}


	/**
	 * ���������� ������ ������
	 * @param $id
	 * @param $data
	 */
	public function updatePlayer( $id, $data )
	{
		$this->update( $data, 'id = '. (int) $id );
	}


	/**
	 * �������� �������
	 * @param $id
	 */
	public function deletePlayer( $id )
	{
		$this->delete('id =' . (int) $id );
	}


	public function deleteByIds( $ids )
	{
		$this->delete('id IN (' . (string) $ids . ')' );
	}

	/**
	 * �������������� ���� � ����������� ���
	 * @param $date
	 * @return string
	 */
	public function dateFormat( $date )
	{
		$date  = new DateTime( $date );
		return $date->format('Y-m-d');
	}


}

