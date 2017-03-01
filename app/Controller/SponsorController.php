<?php
/**
 * Sponsor Controller
 *
 * This file handles all Sponsor related operations
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

App::uses('CakeEmail', 'Network/Email');

class SponsorController extends AppController {

	var $uses = array(
		'Sponsor',
		'Admin',
		'User'
	);

	var $components = array('Validation');

	/**
	 *List sponsors
	 */
	function admin_index() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller'=>'cpanel',
				'action'=>'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Create User model
		$this->loadModel('User');

		// Set conditions
		$data = $this->Sponsor->find(
			'all',
			array(
				'conditions' => array('delete_status' => 0),
				'order'      => 'id desc'
			)
		);
		$this->set('userdata', $data);
		$Admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $Admindata);
	}

	/**
	 * Add a new sponsor
	 */
	public function admin_add() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';
		$checkUser = 0;

		if (!empty($this->request->data)) {
			$exist_Email = $this->Sponsor->find(
				'all',
				array(
					'conditions' => array('email' => $this->request->data['User']['email'])
				)
			);

			foreach ($exist_Email as $deleted_user) {

				if($deleted_user['User']['delete_status'] == '0') {
					$checkUser = 1;
					break;
				}
			}

			// Validate name
			if ($this->Validation->Presence($this->request->data['User']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a name'));
				$this->render();
			}

			// Validate email address
			else if ($this->Validation->Presence($this->request->data['User']['email'])) {
				$this->Session->write('success', "0");
						$this->Session->write('alert', __('You must enter an email address'));
				$this->render();
			} else if ($this->Validation->Email($this->request->data['User']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid email address'));
				$this->render();
			} else if ($checkUser == 1) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Email already registered'));
				$this->render();
			} else {
				$data['User'] = $this->request->data['User'];

				// Set registration date
				$data['User']['registered'] = date('Y-m-d H:i:s');

				// If everything went well
				if ($this->Sponsor->save($data['User'])) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', __('Sponsor creation successful'));
					$this->redirect(
						array(
							'controller' => 'sponsor',
							'action'     => 'index'
						)
					);

				// Otherwise, trigger an error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __('Sponsor creation failed'));
					$this->render();
				}
			}
		}
	}

	/**
	 * Edit a sponsor
	 */
	public function admin_edit($id)  {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		if (!empty($this->request->data)) {
			$exist_Email = $this->Sponsor->find(
				'all',
				array(
					'conditions' => array(
						'email'         =>$this->request->data['Sponsor']['email'],
						'id !='         => $this->request->data['Sponsor']['id'],
						'delete_status' => 0
					)
				)
			);

			// Validate name
			if ($this->Validation->Presence($this->request->data['Sponsor']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a name'));

			// Validate email address
			} else if ($this->Validation->Presence($this->request->data['Sponsor']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an email address'));
			} else if ($this->Validation->Email($this->request->data['Sponsor']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid email address'));
			} else if (!empty($exist_Email)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Email already registered'));
			} else {
				$data['Sponsor'] = $this->request->data['Sponsor'];

				// If everything went well
				if($this->Sponsor->save($this->request->data)) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', __('Sponsor update successful'));

				// Otherwise, trigger an error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __('Sponsor update failed'));
				}
				$this->redirect(
					array(
						'controller' => 'sponsor',
						'action'     => 'index'
					)
				);
			}
			$encoded_id = base64_encode($this->request->data['Sponsor']['id']);
			$this->redirect(
				array(
					'controller' => 'sponsor',
					'action'     => 'edit',
					urlencode($encoded_id)
				)
			);
		} else {

			if (is_numeric(base64_decode($id))) {
				$this->request->data = $this->Sponsor->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Invalid Sponsor"));
			}
		}
	}

	/**
	 * View a sponsor
	 */
	public function admin_view($id)  {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		if (!empty($this->request->data)) {
			$exist_Email = $this->Sponsor->find(
				'all',
				array(
					'conditions' => array(
						'email'         =>$this->request->data['Sponsor']['email'],
						'id !='         => $this->request->data['Sponsor']['id'],
						'delete_status' => 0
					)
				)
			);

			$encoded_id = base64_encode($this->request->data['Sponsor']['id']);
		} else {

			if (is_numeric(base64_decode($id))) {
				$this->request->data = $this->Sponsor->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Invalid Sponsor"));
			}
		}
	}

	/**
	 * Delete a sponsor
	 */
	public function admin_delete($id) {
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);
		$this->autoRender = false;

		if(is_numeric(base64_decode($id))) {
			$data['Sponsor']['id'] = base64_decode($id);
			$data['Sponsor']['delete_status'] = '1';

			if($this->Sponsor->save($data)) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', __("Sponsor deletion successful"));
				$this->redirect(
					array(
						'controller' => 'sponsor',
						'action'     => 'index'
					)
				);
			}
		}
	}

	/**
	 * Check resellers assigned to a sponsor
	 */
	public function admin_resellers($id) {
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);
		$this->layout = 'admin_layout';

		if (is_numeric(base64_decode($id))) {

			$data = $this->User->find(
				'all',
				array(
					'conditions' => array(
						'delete_status' => 0,
						'sponsor_id'    => base64_decode($id)
					),
					'fields'     => array('User.*'),
					'order'      => 'id desc'
				)
			);
			$this->set('userdata', $data);

		}

	}

	/**
	 * List number of sponsors
	 */
	public function total_sponsors() {
		$this->layout = '';
		$data = $this->Sponsor->find(
			'count',
			array(
				'conditions' => array('delete_status' => 0)
			)
		);
		return $data;
	}


	/**
	 * List sponsors
	 */
	public function getSponsor() {
		$this->autoRender = false ;
		$sponsor = $this->Sponsor->find(
			'list',
			array(
				'conditions' => array('delete_status' => 0),
				'fields'     => array('name')
			)
		);
		return $sponsor;
	}
}
