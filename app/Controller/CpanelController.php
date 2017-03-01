<?php
/**
 * CPanel Controller
 *
 * This file controls session sign in and sign out, user
 * profile updates and password changes.
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

App::uses('Security', 'Utility');

class CpanelController extends AppController {
	var $uses = array('Admin');

	/**
	 * Username and password validation for session sign in
	 */
	function admin_index() {

		// Load layout
		$this->layout = '';

		if (!empty($this->request->data)) {

			// Make sure a username is entered
			if($this->request->data['Admin']['username'] == '') {
				$this->Session->write('alert', __("<span style='color:red;'>You must enter a username</span>"));
			}

			// Make sure a password is entered
			if($this->request->data['Admin']['password'] == '') {
				$this->Session->write('alert', __("<span style='color:red;'>You must enter a password</span>"));
			}

			// Check username and password in database
			$res = $this->Admin->find(
				'first',
				array(
					'conditions' => array(
						'username' => $this->request->data['Admin']['username'],
						'password' => Security::hash($this->request->data['Admin']['password'], 'sha1', true)
					)
				)
			);

			// If they match a valid user, trigger the home action and set session values
			if (!empty($res)) {
				$this->Session->write('admin_id', $res['Admin']['id']);
				$this->Session->write('admin_type', $res['Admin']['type']);
				$this->Session->write('admin_username', $res['Admin']['username']);
				$this->Session->write('admin_name', $res['Admin']['name']);
				$this->Session->write('admin_recharge', $res['Admin']['generate_recharge_access']);

				if ($res['Admin']['language'] == 2) {
					$this->Session->write('Config.language', 'spa');
				} else {
					$this->Session->write('Config.language', 'eng');
				}

				$this->redirect(
					array(
						'controller' => 'cpanel',
						'action'     => 'home'
					)
				);

			// Otherwise, display error message
			} else {
				$this->Session->write('alert', __("<span style='color:red;'>Invalid Username or Password</span>"));
			}
		}
	}

	/**
	 * Home action
	 */
	function admin_home() {

		// Check that the session is valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';
	}

	/**
	 * User profile updates (Username, name, email)
	 */
	function admin_profile() {

		// Load standard layout
		$this->layout = 'admin_layout';
		$id = $this->Session->read('admin_id');

		// Save data into staff table
		if (!empty($this->request->data)) {

			// Get data based on id
			$this->Admin->id = $id;

			// Save staff name
			$this->Admin->saveField('name', $this->request->data['Admin']['name']);

			// Save staff username
			$this->Admin->saveField('username', $this->request->data['Admin']['username']);

			// Save staff email address
			$this->Admin->saveField('email', $this->request->data['Admin']['email']);

			// Save staff email address
			$this->Admin->saveField('language', $this->request->data['Admin']['language']);

			// If all goes well, send a successful message
			$this->Session->write('success', "1");
			$this->Session->write('alert', __("Profile update successful"));
			$this->redirect(
				array(
					'controller' => 'cpanel',
					'action'     => 'profile'
				)
			);
		} else {
			$data = $this->Admin->find(
				'first',
				array(
					'conditions' => array(
						'id' => $id
					)
				)
			);
			$this->request->data = $data;
		}
	}

	/**
	 * Password change
	 */
	function admin_change_password() {

		// Check that session is valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Loasd standard layout
		$this->layout = 'admin_layout';

		if ($this->request->data) {
			$id = $this->Session->read('admin_id');

			// Check current password
			$pwd_exists = $this->Admin->find(
				'first',
				array(
					'conditions' => array(
						'id'       => $id,
						'password' => Security::hash($this->request->data['Admin']['currentPassword'], 'sha1', true)
					)
				)
			);

			// If current password is wrong, generate error message
			if (empty($pwd_exists)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Incorrect password"));

			// Otherwise, proceed with change
			} else {

				// If password confirmation is correct, proceed with change
				if ($this->request->data['Admin']['newPassword'] == $this->request->data['Admin']['confirmPassword']) {
					$this->Admin->id = $id;
					$this->Admin->saveField('password', Security::hash($this->request->data['Admin']['newPassword'], 'sha1', true));
					$this->Session->write('success', "1");
					$this->Session->write('alert', __("Password change successful"));
					$this->redirect(
						array(
							'controller' => 'cpanel',
							'action'     => 'change_password'
						)
					);

				// Otherwise, generate error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __("Password confirmation failed"));
				}
			}
		}
	}

	/**
	 * Session sign out
	 */
	function admin_signout() {
		$this->autoRender = false;

		// Destroy current session
		$this->Session->destroy('');

		// Generate success message
		$this->Session->write('alert', __("<span style='color:green;'>Sign out successful</span>"));

		// Redirect back to cpanel index
		$this->redirect(
			array(
				'controller' => 'cpanel',
				'action'     => 'index'
			)
		);
	}

	/**
	 * Check  that current session is still valid
	 */
	function admin_checkSession() {

		// Check current session
		$admin_id = $this->Session->read('admin_id');

		// If session has expired, generate error message
		if ($admin_id == null) {
			$this->Session->write('alert', __("<span style='color:red;'>Your session has expired, please sign in to continue</span>"));

			// Redirect back to cpanel index
			$this->redirect(
				array(
					'controller' => 'cpanel',
					'action'     => 'index',
					'admin'      => true
				)
			);
		}
	}
}
