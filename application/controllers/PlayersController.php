<?php

class PlayersController extends Zend_Controller_Action
{

    function init()
    {
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('checklogin', 'json')
			->initContext();

		// ������������ ������� � ������� ��������� �������
		if( $this->getRequest()->getPost('event') )
		{
			$this->event( $this->getRequest()->getPost('event') );
		}

	}


	/**
	 * ������ �������
	 */
	function indexAction()
    {

		$frontendOptions = array(
			'lifetime' => 10,
			'automatic_serialization' => true,
			'regexps' => array(
				'^/$' => array('cache' => true),
				'^/players/' => array('cache' => true)
			)
		);

		$backendOptions = array('cache_dir' => './tmp/');

		$cache = Zend_Cache::factory('Output','File',
			$frontendOptions,
			$backendOptions);

		// �������� ���������� ������������� ������ start()
		if( ! $playersData = $cache->load('players') ) {
		// ���������� �����, ��� ������:

			$players 		= new Application_Model_DbTable_Players();
			$playersData 	= $players->getAll();

			$cache->save( $playersData );

		}

		$searchForm	= new Application_Form_Search();

		$this->view->searchForm	= $searchForm;
		$this->view->players = $playersData;

    }


	/**
	 * ���������� �������
	 */
	function addAction()
    {

        $form 		= new Application_Form_AddEdit();
		$player 		= new Application_Model_DbTable_Players();

		$this->view->textLegend	= '���������� ������ ������';
		$this->view->subject		 	= 'New Player';

		$this->view->form = $form;

        if ($this->getRequest()->isPost())
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid( $formData ) ) {

				$data = array(
					'username' 	=> $form->getValue('username'),
					'first_name' 	=> $form->getValue('first_name'),
					'last_name' 	=> $form->getValue('last_name'),
					'birth_date' 	=> $player->dateFormat( $form->getValue( 'birth_date' ) ),
					'email' 			=> $form->getValue('email'),
					'admin_id'		=> $form->getValue('admin_id')
				);

				$player->addPlayer( $data );

                $this->redirect('/');
            } else {
                $form->populate( $formData );
            }
        }

    }


	/**
	 * �������������� ������
	 */
	function editAction()
    {
		$players 	= new Application_Model_DbTable_Players();
		$form 		= new Application_Form_AddEdit();
		$id 			= $this->_getParam('id', 0);

		$form->username
			->setAttrib('readonly', 'true')
			->removeValidator('Db_NoRecordExists');

        if ( $this->getRequest()->isPost() )
		{
            $formData = $this->getRequest()->getPost();
            if ( $form->isValid( $formData ) )
			{
				$data = array(
					'username' 	=> $form->getValue('username'),
					'first_name' 	=> $form->getValue('first_name'),
					'last_name' 	=> $form->getValue('last_name'),
					'birth_date' 	=> $players->dateFormat( $form->getValue( 'birth_date' ) ),
					'email' 			=> $form->getValue('email'),
					'admin_id'		=> $form->getValue('admin_id')
				);

				$players->updatePlayer( $id, $data );

                $this->redirect('/');

            } else {
                $form->populate( $formData );
            }
        } else {

				$data = $players->getPlayer( $id );

				$this->view->textLegend	= '�������������� ������';
				$this->view->subject		 	= $data['username'];
				$this->view->form 				= $form;

                $form->populate( $data );

        }
    }


	/**
	 * �������� ������
	 */
	public function deleteAction()
    {
		$id = $this->_getParam('id', 0);

		$players = new Application_Model_DbTable_Players();
		$players->deletePlayer( $id );

		$this->redirect('/');
    }


	/**
	 * �������� ������� ����� ������
	 * Ajax
	 */
	public function checkloginAction()
	{

		if( $this->getRequest()->isPost() )
		{
			$username		= $this->getRequest()->getPost('username');
			$player 			= new Application_Model_DbTable_Players();

			if( $player->getByName( $username ) ) {
				$match = true;

			} else {
				$match = false;
			}

			$this->_helper->json( array(
				'match' => $match
			) );
		}

	}



	/**
	 * ���������� ������� � ������� ��������� �������
	 * ���� ��� ���� �������, �� ��� ����� ���������
	 * @param $event
	 * @return bool
	 */
	private function event( $event )
	{
		$player 	= new Application_Model_DbTable_Players();

		if( $items 	= $this->getRequest()->getPost('check') )
		{
			$ids = '';
			foreach( $items as $id)
			{
				$ids .= $id.", ";
			}
			$ids = substr($ids, 0, -2);

			if($event == 'delete') 	{
				$player->deleteByIds($ids);
			}

		}
		return true;
	}


	/**
	 * ����� �������
	 */
	public function searchAction()
	{
		$condition 	= '';
		$form 		= new Application_Form_Search();
		$admins		= new Application_Model_DbTable_Administrators();
		$players 	= new Application_Model_DbTable_Players();
		$columns	= $players->info('cols');

		$formData = $this->getRequest()->getQuery();
		if ( ! empty( $formData ) )
		{
			// ���������� ��� GET-��������� � ���������� �� � ���������� ����� �������,
			// ��� �������������� ������
			foreach ( $formData as $key => $val )
			{
				if ( ! empty( $val ) )
				{
					if ( in_array( $key, $columns ) )
					{
						// ���� ������ ��� ������, �� ��������� ��� � ID ������
						if ( $key == 'admin_id') {
							$admin_name = $admins->getByName( $val )->id;
							$val = ($admin_name) ? $admin_name : 'null';
						}

						// ��������� ���� � ����������� ���
						if ( $key == 'birth_date') {
							$val = $players->dateFormat( $val );
						}

						// ��������� �������
						$condition .= 'p.' .  $key . " LIKE '%" .  $val  . "%' OR ";
					}
				}
			}


			// ���� �� �������������� �������, �� ������ �� �������
			if ( !$condition ) {
				$condition = ' 0 ';
				$this->view->errorEmpty = true;
			} else {
				$condition = substr( $condition, 0, -3 );
			}

		}
		else {
			$condition = ' 0 ';
		}


		// ��������� �������� ����� ������
		$this->view->showSearchForm = true;
		$this->view->searchForm = $form;
		$form->populate( $formData );

		$this->view->players 	= $players->getAll( $condition );
		$this->view->count 	= $players->count;
		$this->render('index');
	}


}

