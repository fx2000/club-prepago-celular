<?php
/**
 * Staff Controller
 *
 * This file handles all Staff related operations
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class StaffController extends AppController {

	var $uses = array('Admin');
	var $components = array('Validation');

	/**
	 * List staff members
	 */
	function admin_index() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Check current user type
		$admin_type = $this->Session->read('admin_type');

		// If current user is a supervisor, only display support staff
		if ($admin_type == 2) {
			$condition = array('type' => 1);

		// If current user is a manager, display everyone
		} else {
			$condition = array('type !=' => 4);
			$data = $this->Admin->find(
				'all',
				array(
					'conditions' => $condition,
					'order'      => 'id desc'
				)
			);
			$this->set('userdata', $data);
		}
	}

	/**
	 * Add a new staff member
	 */
	public function admin_add () {

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

			// Check if username already exists
			$exist_Username = $this->Admin->find(
				'all',
				array(
					'conditions' => array(
						'username' => $this->request->data['Admin']['username']
					)
				)
			);

			// Check if email already exists
			$exist_Email = $this->Admin->find(
				'all',
				array(
					'conditions' => array(
						'email' => $this->request->data['Admin']['email']
					)
				)
			);

			// Validate name presence
			if ($this->Validation->Presence($this->request->data['Admin']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Name cannot be blank");
				$this->render();

			// Validate email address presence
			} else if ($this->Validation->Presence($this->request->data['Admin']['email'])) {
				$this->Session->write('success',"0");
				$this->Session->write('alert', "Email cannot be blank");
				$this->render();

			// Validate email address format
			} else if ($this->Validation->Email($this->request->data['Admin']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Invalid email");
				$this->render();

			// Check if email address is already registered
			} else if (!empty($exist_Email)) {
				$this->Session->write('success', "0");
					$this->Session->write('alert', "Email already exists");
				$this->render();

			// Validate username presence
			} else if ($this->Validation->Presence($this->request->data['Admin']['username'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Username cannot be blank");
				$this->render();

			// Validate is username already exists
			} else if (!empty($exist_Username)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Username already exists");
				$this->render();

			// Validate password presence
			} else if ($this->Validation->Presence($this->request->data['Admin']['password'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Password cannot be blank");
				$this->render();

			// Validate confirmation password presence
			} else if ($this->Validation->Presence($this->request->data['Admin']['confirm_password'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Password confirmation cannot be blank");
				$this->render();

			// Check if passwords match
			} else if ($this->request->data['Admin']['confirm_password'] != $this->request->data['Admin']['password']) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Passwords do not match");
				$this->render();

			// Save and encrypt password
			} else {
				$data['Admin'] = $this->request->data['Admin'];
				$data['Admin']['password'] = Security::hash($data['Admin']['password'], 'sha1', true);

				// If all goes well, save and display a success message
				if ($this->Admin->save($data['Admin'])) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', "Staff created successfully");

					// Redirect user back to staff index
					$this->redirect(
						array(
							'controller' => 'staff',
							'action'     => 'index'
						)
					);

				// Otherwise, display error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', "Staff could not be created");
					$this->render();
				}
			}
		}
	}

	/**
	 * Edit a staff member
	 */
	public function admin_edit($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		if (is_numeric(base64_decode($id))) {

			// Check if username already exists
			if (!empty($this->request->data)) {
				$exist_Username = $this->Admin->find(
					'all',
					array(
						'conditions' => array(
							'username' => $this->request->data['Admin']['username'],
							'id !='    => base64_decode($id)
						)
					)
				);

				// Check if email already exists
				$exist_Email = $this->Admin->find(
					'all',
					array(
						'conditions' => array(
							'email' => $this->request->data['Admin']['email'],
							'id !=' => base64_decode($id)
						)
					)
				);

				// Validate name presence
				if ($this->Validation->Presence($this->request->data['Admin']['name'])) {
					$this->Session->write('success', "0");
					$this->Session->write('alert', "Name cannot be blank");

				// Validate email address presence
				} else if ($this->Validation->Presence($this->request->data['Admin']['email'])) {
					$this->Session->write('success', "0");
					$this->Session->write('alert', "Email cannot be blank");

				// Validate email address format
				} else if ($this->Validation->Email($this->request->data['Admin']['email'])) {
					$this->Session->write('success', "0");
					$this->Session->write('alert', "Invalid email");

				// Check if email address is already registered
				} else if (!empty($exist_Email)) {
					$this->Session->write('success', "0");
					$this->Session->write('alert', "Email already exists");

				// Validate username presence
				} else if ($this->Validation->Presence($this->request->data['Admin']['username'])) {
					$this->Session->write('success', "0");
					$this->Session->write('alert', "Username cannot be blank");

				// Validate is username already exists
				} else if (!empty($exist_Username)) {
					$this->Session->write('success', "0");
					$this->Session->write('alert', "Username already exists");
				} else {
					$data['Admin'] = $this->request->data['Admin'];
					$this->Admin->id = base64_decode($id);

					// Save, if everything went well, display success message
					if ($this->Admin->save($this->request->data)) {
						$this->Session->write('success', "1");
						$this->Session->write('alert', "Staff updated successfully");

					// Otherwise, display error message
					} else {
						$this->Session->write('success', "0");
						$this->Session->write('alert', "Staff could not be updated");
					}

					// Redirect back to staff index
					$this->redirect(
						array(
							'controller' => 'staff',
							'action'     => 'index'
						)
					);
				}

				// Redirect back to staff edit
				$this->redirect(
					array(
						'controller' => 'staff',
						'action'     => 'edit',
						$id
					)
				);
			} else {
				$this->request->data = $this->Admin->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);
			}

		// If staff member id is invalid
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', "Invalid Staff");
		}
	}

	/**
	 * Delete a staff member
	 */
	public function admin_delete($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);
		$this->autoRender = false;

		// Check staff member id
		if (is_numeric(base64_decode($id))) {

			// Delete the record and show a success message
			if ($this->Admin->delete(base64_decode($id))) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', "Staff deleted successfully");

				// Redirect back to staff index
				$this->redirect(
					array(
						'controller' => 'staff',
						'action'     => 'index'
					)
				);
			}
		}
	}

	/**
	 * What does this function do?
	 */
	public function admin_change_password($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		//Load standard layout
		$this->layout = 'admin_layout';

		if($this->request->data) {
			$staff_id = base64_decode($id);

			// Check that password match
			if($this->request->data['Admin']['new_pwd'] == $this->request->data['Admin']['confirm_pwd']) {
				$this->Admin->id = $staff_id;
				$this->Admin->saveField('password', Security::hash($this->request->data['Admin']['new_pwd'], 'sha1', true));

				// Save the change and display a success message
				$this->Session->write('success', "1");
				$this->Session->write('alert', "Password changed successfully");

				// Redirect back to staff index
				$this->redirect(
					array(
						'controller' => 'staff',
						'action'     => 'index'
					)
				);

			// Otherwise display a failure message
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Password confirmation failed");
			}
		}
	}

	/**
	 * List number of staff members
	 */
	public function total_staff() {
		$this->layout = '';
		$data = $this->Admin->find(
			'count',
			array(
				'conditions' => array('id')
			)
		);
		return $data;
	}
}
