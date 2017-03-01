 <?php
/**
 * Setting Controller
 *
 * This file handles the settings section of the system
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class SettingController extends AppController {

	var $uses = array(
		'Setting',
		'Country',
		'Operator',
		'OperatorCredential'
	);

	var $components = array('Validation');

	/**
	 * Reward point value settings
	 */
	public function admin_edit_points() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Validate that all values are present
		if (!empty($this->request->data)) {

			if ($this->Validation->Presence($this->request->data['Setting']['reward_signup'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Reward points for signup cannot not be blank"));
				$this->render();
			} else if ($this->Validation->Presence($this->request->data['Setting']['reward_referral'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Reward points for refferals cannot be blank"));
				$this->render();
			} else if ($this->Validation->Presence($this->request->data['Setting']['reward_recharge'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Reward points for recharges cannot be blank"));
				$this->render();
			} else if($this->Validation->Presence($this->request->data['Setting']['reward_social'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Reward points for shares or likes cannot be blank"));
				$this->render();
			} else {
				$data['Setting'] = $this->request->data['Setting'];
				$this->Setting->id = '1';


				// If everything was saved correctly, return a success message
				if ($this->Setting->save($this->request->data)) {
					print_r($this->request->data);
					$this->Session->write('success', "1");
					$this->Session->write('alert', "Settings saved successfully");

				// If saving failed
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', "Settings could not be saved");
				}
			}

			// Redirect user back to edit points
			$this->redirect(
				array(
					'controller' => 'setting',
					'action'     => 'edit_points'
				)
			);
		}  else {
			$this->request->data = $this->Setting->find('first');
		}
	}

	/**
	 * Tax settings
	 */
	public function admin_tax() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';


		// Find data in countries table
		if (!empty($this->request->data)) {
			$countries = $this->Country->find('all');

			// Display tax rate for each country in the table
			foreach ($countries as $country) {
				$var  ='tax'.$country['Country']['id'];
				$tax['Country']['tax'] = $this->request->data['Country'][$var];
				$tax['Country']['id'] = $country['Country']['id'];

				// Save new tax rate
				$this->Country->save($tax);

				// If everything worked, display a success message
				$this->Session->write('success', "1");
				$this->Session->write('alert', "Tax rate saved successfully");
			}

			// Redirect the user back to taxes screen
			$this->redirect(
				array(
					'controller' => 'setting',
					'action'     => 'tax'
				)
			);
		}
		$this->request->data = $this->Country->find('all');
	}

	/**
	 * Show mobile operator status
	 */
	public function admin_operator() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Get all data from operators table
		$this->request->data = $this->Operator->find('all');

	}

	/**
	 * Activate/Deactivate mobile operators
	 */
	public function admin_operator_change() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Get mobile operator data
		$operator['id'] = base64_decode($this->params['pass'][0]);
		$operator['status'] = $this->params['pass'][1];

		// If status change was successful
		if ($this->Operator->save($operator)) {
			$this->Session->write('success', "1");
			$this->Session->write('alert', "Operator status changed successfully");

		// If status change failed
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', "Operator status could not be changed");
		}

		// Redirect back to Operator menu
		$this->redirect(
			array(
				'controller' => 'setting',
				'action'     => 'operator'
			)
		);
	}

	/*
	 * View TrxEngine Settings
	 */
	public function admin_view_platform() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Get data from operators and operator_credentials table
		$options = array(
			'joins'  => array(
					array(
						'table'      => 'operator_credentials',
						'alias'      => 'OperatorCredential',
						'type'       => 'left',
						'foreignKey' => false,
						'conditions' => array('OperatorCredential.operator_id = Operator.id')
					)
				) ,
			'fields' => array('OperatorCredential.*,Operator.*')
			);
		$operators_credentials = $this->Operator->find('all', $options);
		$this->set('operators_credentials', $operators_credentials);
	}

	/*
	 * Edit TrxEngine Settings
	 */
	public function admin_edit_platform($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// If an operator id is provided
		if (is_numeric(base64_decode($id))) {

			// Get current settings
			if (!empty($this->request->data)) {
				$data = $this->request->data['OperatorCredential'];
				$exstoperator = $this->OperatorCredential->find(
					'first',
					array(
						'conditions' => array(
							'operator_id' => $data['operator_id']
						)
					)
				);

				// If changes are made
				if (empty($exstoperator)) {

					// If changes are made correctly, save data and show success message
					if ($this->OperatorCredential->save($data)) {
						$this->Session->write('success',"1");
						$this->Session->write('alert', __("TrxEngine settings update successful"));

						// Redirect back to view platform
						$this->redirect(
							array(
								'controller' => 'setting',
								'action'     => 'view_platform'
							)
						);

					// Otherwise, display error message
					} else {
						$this->Session->write('success',"0");
						$this->Session->write('alert', __("TrxEngine settings update failed"));

						// And go back to where you were
						$this->redirect($this->referer());
					}

				// If other chanegs are made
				} else {
					$updateData['ip_address'] = "'" . $data['ip_address'] . "'";
					$updateData['username'] = "'" . $data['username'] . "'";
					$updateData['port'] = "'" . $data['port'] . "'";
					$updateData['product_id'] = "'" . $data['product_id'] . "'";

					// If changes were made correctly, save data and show success message
					if ($this->OperatorCredential->updateAll($updateData, array('operator_id' => $data['operator_id']))) {
						$this->Session->write('success',"1");
						$this->Session->write('alert', __("TrxEngine settings update successful"));

						// Redirect back to view platform
						$this->redirect(
							array(
								'controller' => 'setting',
								'action'     => 'view_platform'
							)
						);

					// Otherwise, display error message
					} else {
						$this->Session->write('success',"0");
						$this->Session->write('alert', __("TrxEngine settings update failed"));

						// And go bacck to where you were
						$this->redirect($this->referer());
					}
				}
			} else {
				$options = array(
					'joins'  => array(
							array(
								'table'      => 'operator_credentials',
								'alias'      => 'OperatorCredential',
								'type'       => 'inner',
								'foreignKey' => false,
								'conditions' => array('OperatorCredential.operator_id = Operator.id','OperatorCredential.operator_id'=>base64_decode($id))
							)
						) ,
					'fields' => array('OperatorCredential.*,Operator.*')
				);
				$this->request->data = $this->Operator->find('first', $options);

				if (empty($this->request->data)) {
					$this->request->data = $this->Operator->findById(base64_decode($id));
				}
			}
		}
	}

	/*
	 * Change TrxEngine password
	 */
	public function admin_change_password($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// If a change is requested, check all details
		if (!empty($this->request->data)) {
			$id = base64_decode($id);
			$data = $this->request->data['OperatorCredential'];

			// If new password matches confirmation password
			if ($data['password'] == $data['confirm_password']) {
				$updateData['password'] = "'" . $data['password'] . "'";

				// Save and display success message
				if ($this->OperatorCredential->updateAll($updateData,array('operator_id' => $id))) {
					$this->Session->write('success',"1");
					$this->Session->write('alert', __("Password change successful"));

					// Redirect the user back to view platform
					$this->redirect(array(
						'controller' => 'setting',
						'action'     => 'view_platform'));

				// If something went wrong, show generic error message
				} else {
					$this->Session->write('success',"0");
					$this->Session->write('alert', __("Password change failed"));

					// And go back to where you were
					$this->redirect($this->referer());
				}

			// If password confirmation failed, diplay error message
			} else {
				$this->Session->write('success',"0");
				$this->Session->write('alert', __("Password confirmation failed"));
			}
		}
	}

	/**
	 * Set reseller fees and discounts
	 */
	public function admin_reseller()  {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Get current settings
		if (!empty($this->request->data)) {
			$this->request->data['Setting']['id'] = 1;

			// Save changes
			$this->Setting->save($this->request->data);

			// If everything went well, show success message
			$this->Session->write('success', "1");
			$this->Session->write('alert', __("Settings saved successfully"));

			// Redirect back to reseller settings
			$this->redirect(
				array(
					'controller' => 'setting',
					'action'     => 'reseller'
				)
			);
		}
		$this->request->data = $this->Setting->find('first');
	}

	/**
	 * Get list of countries
	 */
	public function getCountries() {
		$this->autoRender = false;
		return $this->Country->find('list');
	}

}
