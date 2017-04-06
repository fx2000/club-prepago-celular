<?php
/**
 * User Controller
 *
 * This file handles all User related operations
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

App::uses('CakeEmail', 'Network/Email');

class UserController extends AppController {

	var $uses = array(
		'User',
		'Recharge',
		'Redemption',
		'Reward',
		'Admin',
		'AccountHistory',
		'Setting',
		'Payment'
	);

	var $components = array('Validation');


	/**
	 * List users
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

		// Create user model
		$this->loadModel('User');

		// Set conditions
		$data = $this->User->find(
			'all',
			array(
				'conditions' => array(
					'delete_status' => 0,
					'user_type'     => 1
				),
				'order'      => 'id desc'
			)
		);
		$this->set('userdata',$data);
		$Admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $Admindata);
	}

	/**
	 * Add a new user
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
			$exist_Email = $this->User->find(
				'all',
				array(
					'conditions' => array('email' => $this->request->data['User']['email'])
				)
			);

			// Check if the email address belongs to a deleted user
			foreach ($exist_Email as $deleted_user) {

				if ($deleted_user['User']['delete_status'] == '0') {
					$checkUser = 1;
					break;
				}
			}

			// Validate presence of name
			if ($this->Validation->Presence($this->request->data['User']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a name'));
				$this->render();

			// Validate presence of Cedula or Passport
			}else if ($this->Validation->Presence($this->request->data['User']['tax_id'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a Cedula or Passport number'));
				$this->render();

			// Validate presence of email address
			} else if ($this->Validation->Presence($this->request->data['User']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an email address'));
				$this->render();

			// Validate email address format
			} else if ($this->Validation->Email($this->request->data['User']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid email address'));
				$this->render();

			// Check if email already exists
			} else if ($checkUser == 1) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Email already registered'));
				$this->render();

			// Validate presence of address
			} else if ($this->Validation->Presence($this->request->data['User']['address'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an address'));
				$this->render();

			// Validate presence of phone number
			} else if($this->Validation->Presence($this->request->data['User']['phone_number'])) {
				$this->Session->write('success',"0");
				$this->Session->write('alert',__('You must enter a phone number'));
				$this->render();
			} else {

				// Create user
				$data['User'] = $this->request->data['User'];

				// Set registration date and time
				$data['User']['registered'] = date('Y-m-d H:i:s');

				// Set email verification and status to 0
				$data['User']['email_verify'] = 0;
				$data['User']['status'] = 0;
				$setting = $this->Setting->findById('1');

				// Grant sign up reward points
				$data['User']['points'] = $setting['Setting']['reward_signup'];

				// Set user type
				$data['User']['user_type'] = 1;

				// Auto generate and encrypt password
				$password = $this->Validation->generatePassword();
				$data['User']['password'] = Security::hash($password, 'sha1', true);

				// Send account activation email
				if ($this->User->save($data['User'])) {

					// Generate activation code
					$enc_uid = sha1($this->User->id);

					// Generate activation url
					$activation_url	= Router::url('/home/activate/', true) . $enc_uid;

					// Set email details
					$Email = new CakeEmail();
					$Email->template('welcome');
					$Email->emailFormat('html');
					$Email->config('smtp');
					$Email->to($data['User']['email']);
					$Email->subject('¡Bienvenido a Club Prepago Celular!');

					// Set Email body variables
					$Email->viewVars(
						array(
							'username'           => $data['User']['name'],
							'email_address'      => $data['User']['email'],
							'password'           => $password,
							'url'                => $activation_url
						)
					);

					// Send email message
					$Email->send();

					// Generate success message
					$this->Session->write('success', "1");
					$this->Session->write('alert', __('User creation successful'));
					echo "<script>window.location.href='index'</script>";
					exit;

				// If there was a problem, generate error message
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __('User creation failed'));
					$this->render();
				}
			}
		}
	}

	/**
	 * View a user
	 */
	public function admin_view($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';


		// Get user's datafrom users table
		if (!empty($this->request->data)) {
			$exist_Email = $this->User->find(
				'all',
				array(
					'conditions' => array(
						'email'         => $this->request->data['User']['email'],
						'id !='         => $this->request->data['User']['id'],
						'delete_status' => 0
					)
				)
			);

			$encoded_id = base64_encode($this->request->data['User']['id']);
			echo "<script>window.location.href='../edit/" . urlencode($encoded_id) . "'</script>";
			exit;

		// Find user by id
		} else {

			if (is_numeric(base64_decode($id))) {
				$this->request->data=$this->User->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);

			// If user could not be found, trigger an error
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid User'));
			}
		}
	}

	/**
	 * Edit a user
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


		// Get user's datafrom users table
		if (!empty($this->request->data)) {
			$exist_Email = $this->User->find(
				'all',
				array(
					'conditions' => array(
						'email'         => $this->request->data['User']['email'],
						'id !='         => $this->request->data['User']['id'],
						'delete_status' => 0
					)
				)
			);

			// Validate name
			if ($this->Validation->Presence($this->request->data['User']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a name'));

			// Validate Cedula or Passport
			} else if ($this->Validation->Presence($this->request->data['User']['tax_id'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a Cedula or Passport number'));

			// Validate presence of email address
			} else if ($this->Validation->Presence($this->request->data['User']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an email address'));

			// Validate email address format
			} else if ($this->Validation->Email($this->request->data['User']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid email address'));

			// Check if email already exists
			} else if (!empty($exist_Email)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Email already registered'));

			// Validate presence of address
			} else if ($this->Validation->Presence($this->request->data['User']['address'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an address'));

			// Validate presence of phone number
			} else if($this->Validation->Presence($this->request->data['User']['phone_number'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a phone number'));
			} else {
				$data['User'] = $this->request->data['User'];

				// If User update was successful
				if($this->User->save($this->request->data)) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', __('User update successful'));

				// If User update failed
				} else {
					$this->Session->write('success', "0");
					$this->Session->write('alert', __('User update failed'));
				}
				echo "<script>window.location.href='../index'</script>";
				exit;
			}
			$encoded_id = base64_encode($this->request->data['User']['id']);
			echo "<script>window.location.href='../edit/" . urlencode($encoded_id) . "'</script>";
			exit;

		// Find user by id
		} else {

			if (is_numeric(base64_decode($id))) {
				$this->request->data=$this->User->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);

			// If user could not be found, trigger an error
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid User'));
			}
		}
	}

	/**
	 * Delete a user
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

		// Update users table and set delete status to 1
		if (is_numeric(base64_decode($id))) {
			$data['User']['id'] = base64_decode($id);
			$data['User']['delete_status'] = '1';

			// Trigger a success message
			if ($this->User->save($data)) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', __('User deleted successfully'));
				echo "<script>window.location.href='../index'</script>";
				exit;
			}
		}
	}

	/**
	 * View and edit a user's account, manually add or remove airtime
	 */
	public function admin_account($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Get staff member information
		$Admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $Admindata);

		// Load standard layout
		$this->layout = 'admin_layout';

		// If a user id is received
		if (!empty($this->request->data)) {

			// Start assembling update query
			$qry = 'UPDATE users SET ';

			// Decode user id and store in array
			$UserInfo = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'id' => base64_decode($id)
					),
					'order'      => 'id desc'
				)
			);

			if ($this->request->data['User']['name'] == 1) {

				// If you try to extract too much balance
				if ($UserInfo['User']['balance'] < $this->request->data['User']['amount'] &&
					$this->request->data['User']['action'] == 2) {

						// Display error message
						$this->Session->write('success', "0");
						$this->Session->write('alert', __("You can't extract an amount higher than the current balance of the account"));

						// Redirect back to Account
						$this->redirect(
							array(
								'controller' => 'user',
								'action'     => 'account',
								$id
							)
						);
				}

				// Continue assembling update query
				$qry .= ' balance = balance ';

				// Get update amount from array
				$amount = $this->request->data['User']['amount'];
			} else if ($this->request->data['User']['name'] == 2) {

				// If you try to extract too many points
				if ($UserInfo['User']['points'] < $this->request->data['User']['amount'] &&
					$this->request->data['User']['action'] == 2) {

						// Display error message
						$this->Session->write('success', "0");
						$this->Session->write('alert', __("You can't extract more points than are available in the account"));

						// Redirect back to Account
						$this->redirect(
							array(
								'controller' => 'user',
								'action'     => 'account',
								$id
							)
						);
				}

				// Continue assembling update query
				$qry .= ' points = points ';

				// Get amount from array
				$amount = $this->request->data['User']['amount'];
			}

			// If adding balance
			if ($this->request->data['User']['action'] == 1) {
				$qry .= ' + ';
				$action = '+';

			// If subtracting balance
			} else if ($this->request->data['User']['action'] == 2) {
				$qry .= ' - ';
				$action = '-';
			}

			// Continue assembling update query
			$qry .= $amount . ' WHERE id=\'' . base64_decode($id) . '\' ';

			// Excecute update query on users table
			$data = $this->User->query($qry);

			// Gather data for account_history table update
			$history = array(
				'AccountHistory' => array(
					'detail'       => $this->request->data['User']['detail'],
					'amount'       => $action . $amount,
					'user_id'      => base64_decode($id),
					'account_type' => $this->request->data['User']['name'],
					'staff_id'     => $Admindata['Admin']['id']
				)
			);

			// If everything went well, generate success message
			if ($this->AccountHistory->save($history)) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', __('Account update successful'));

			// If it failed, generate error message
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Account update failed'));
			}

			// Display account history
			$AccHistory = $this->AccountHistory->find(
				'all',
				array(
					'conditions' => array('user_id' => base64_decode($id))
				)
			);
			$this->set('AccHistory', $AccHistory);
			$this->request->data = $this->User->find(
				'first',
				array(
					'conditions' => array('id' => base64_decode($id))
				)
			);
		} else {

			if (is_numeric(base64_decode($id))) {
				$AccHistory = $this->AccountHistory->find(
					'all',
					array(
						'conditions' => array('user_id' => base64_decode($id))
					)
				);
				$this->set('AccHistory', $AccHistory);
				$this->request->data = $this->User->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);

			// If no user or an invalid user is specified
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid User'));
			}
		}
	}

	/**
	 * Check a user's purchase history
	 */
	public function admin_transactions($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Get user's id
		$Admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $Admindata);

		// Decode user's id and search recharges table for user's transactions
		if(is_numeric(base64_decode($id))) {
			$data = $this->Recharge->find(
				'all',
				array(
					'conditions' => array('user_id' => base64_decode($id)),
					'fields'     => array(
						'Recharge.*',
						'Operator.name'
					),
					'order'      => 'id desc',
					'joins'      => array(
						array(
							'table'      => 'operators',
							'alias'      => 'Operator',
							'type'       => 'INNER',
							'conditions' => array('Recharge.operator=Operator.id')
						)
					)
				)
			);
			$this->set('userdata', $data);
		}
	}

	/**
	 * View user airtime purchases
	 */
	public function admin_purchases($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		//Get current staff member details
		$admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $admindata);

		// Set search conditions
		$condition['Payment.user_id'] = base64_decode($id);
		$condition['Payment.status'] = 1;

		// Get information from payments table
		$userdata = $this->Payment->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array('Payment.*')
			)
		);

		// Store payment information in userdata
		$this->set('userdata', $userdata);
	}

	/**
	 * Check a user's reward history
	 */
	public function admin_rewards($id) {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action' => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		//Get current staff member details
		$admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $admindata);

		// Find user ID in redemptions table
		if (is_numeric(base64_decode($id))) {
			$data = $this->Redemption->find(
				'all',
				array(
					'fields'     => array(
						'Reward.*',
						'Redemption.*'
					),
					'conditions' => array(
						'user_id' => base64_decode($id)
					),
					'joins'      => array(
						array(
							'table'      => 'rewards',
							'alias'      => 'Reward',
							'type'       => 'INNER',
							'conditions' => array('Redemption.reward_id=Reward.id')
						)
					)
				)
			);
			$this->set('userdata', $data);
		}
	}

	/**
	 * Export the list of users
	 */
	public function admin_export() {
		$this->autoRender = false;
		$Searchdata = json_decode($this->data['User']['data']);
		$sortFields = array(
			'name',
			'email',
			'registered_date',
			'status'
		);
		$searchVal = $Searchdata->oSearch->sSearch;
		$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];

		if ($sortBy == 'status') {
			$Sort = ($Searchdata->aaSorting[0][1] == 'desc') ? 'asc' : 'desc';
		} else {
			$Sort = $Searchdata->aaSorting[0][1];
		}

		// Set conditions
		$conditions = array(
			'delete_status' => 0,
			'user_type'     => 1
		);

		// Find data for the file
		if (@$searchVal != '') {
			$conditions[] =
				"(email LIKE (\"%" .
				$searchVal . "%\") OR name LIKE (\"%" .
				$searchVal . "%\") OR  DATE_FORMAT(registered_date,\"%d %b, %Y\") LIKE (\"%" .
				$searchVal . "%\"))";
		}

		if ($sortBy != '' && $Sort != '') {
			$orderBY = $sortBy . ' ' . $Sort;
		} else {
			$orderBY = 'id desc';
		}

		// Get data from users table
		$data = $this->User->find(
			'all',
			array(
				'conditions' => $conditions,
				'order'      => $orderBY
			)
		);
		$content = '';

		// Generate column headers
		if (!empty($data)) {
			$content .=
				__("id,Name,Cedula or Passport,Email,Address,City,State or Province,") .
				__("Country,Phone Number,Registration Date,Status,Verified,Banned") .
				"\n";

			// Fill rows with data
			foreach ($data as $user) {

				// Translate country code
				if ($user['User']['country_id'] == 1) {
					$country = 'Panama';
				} else {
					$country = $user['User']['country_id'];
				}

				// Translate status code
				if ($user['User']['status'] == 0) {
					$status = __('Inactive');
				} else if ($user['User']['status'] == 1) {
					$status = __('Active');
				} else {
					$status = $user['User']['status'];
				}

				// Translate email verify code
				if ($user['User']['email_verify'] == 0) {
					$email_verify = __('No');
				} else if ($user['User']['email_verify'] == 1) {
					$email_verify = __('Yes');
				} else {
					$email_verify = $user['User']['email_verify'];
				}

				// Translate banned code
				if ($user['User']['banned'] == 0) {
					$banned = __('No');
				} else if ($user['User']['banned'] == 1) {
					$banned = __('Yes');
				} else {
					$banned = $user['User']['banned'];
				}

				$content .=
					$user['User']['id'] . "," .
					$user['User']['name'] . "," .
					$user['User']['tax_id'] . "," .
					$user['User']['email'] . "," .
					"\"" . $user['User']['address'] . "\"" . "," .
					$user['User']['city'] . "," .
					$user['User']['state'] . "," .
					$country . "," .
					$user['User']['phone_number'] . "," .
					$user['User']['registered'] . "," .
					$status . "," .
					$email_verify . "," .
					$banned . "," .
					"\n";
			}
		}

		// Generate new file
		$path = realpath('../../app/webroot/uploads/') . '/';
		$FileName = __('Users.csv');
		$NewFile = $path . $FileName;
		file_put_contents($NewFile, $content);
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $FileName . '"');
		readfile($NewFile);
		exit();
	}

	/**
	 * Export a user's transactions
	 */
	public function admin_export_transactions() {
		$this->autoRender = false;

		if ($_REQUEST['user_id']) {
			$Searchdata = json_decode($this->data['User']['data']);
			$sortFields = array(
				'Operator.name',
				'Recharge.phone_number',
				'Recharge.amount',
				'Recharge.recharge_date',
				'Recharge.transaction_id',
				'Recharge.points',
				'Recharge.status'
			);
			$searchVal = $Searchdata->oSearch->sSearch;
			$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];

			if ($sortBy == 'Recharge.status') {
				$Sort = ($Searchdata->aaSorting[0][1] == 'desc') ? 'asc' : 'desc';
			} else {
				$Sort = $Searchdata->aaSorting[0][1];
			}
			$conditions = array('user_id' => base64_decode($_REQUEST['user_id']));

			// Set conditions
			if (@$searchVal != '') {
				$conditions[] =
					"(Recharge.transaction_id LIKE (\"%" . $searchVal .
					"%\") OR Operator.name LIKE (\"%" . $searchVal .
					"%\") OR Recharge.phone_number LIKE (\"%" . $searchVal .
					"%\") OR Recharge.amount LIKE (\"%" . $searchVal .
					"%\") OR  DATE_FORMAT(Recharge.recharge_date,\"%d %b, %Y %h:%i %p\") LIKE (\"%" . $searchVal .
					"%\") OR Recharge.points LIKE (\"%" . $searchVal .
					"%\"))";
			}

			if ($sortBy != '' && $Sort != '') {
				$orderBY = $sortBy . ' ' . $Sort;
			} else {
				$orderBY = 'id desc';
			}

			// Find data in recharges table
			$data = $this->Recharge->find(
				'all',
				array(
					'conditions' => $conditions,
					'fields'     => array(
						'Recharge.*',
						'Operator.name'
					),
					'order'      => $orderBY,
					'joins'      => array(
						array(
							'table'      => 'operators',
							'alias'      => 'Operator',
							'type'       => 'INNER',
							'conditions' => array('Recharge.operator=Operator.id')
						),
						array(
							'table'      => 'users',
							'alias'      => 'User',
							'type'       => 'INNER',
							'conditions' => array('Recharge.user_id=User.id')
						)
					)
				)
			);
			$content = '';

			// Generate column headers
			if (!empty($data)) {
				$content .=
					__("Transaction ID,Mobile Operator,Phone Number,Payment Method,Amount,Date & Time,Points Awarded,Status,X,Y") .
					"\n";

				foreach ($data as $recharge) {

					// Translate status codes
					if ($recharge['Recharge']['status'] == 0) {
						$rechargeStatus =  __('Failed');
					} else if ($recharge['Recharge']['status'] == 1) {
						$rechargeStatus =  __('Successful');
					} else if ($recharge['Recharge']['status'] == 2) {
						$rechargeStatus =  __('Replaced');
					}

					// Translate Payment method
					if ($recharge['Recharge']['payment_method'] == 1) {
						$paymentMethod =  __('Prepaid Balance');
					} else if ($recharge['Recharge']['payment_method'] == 2) {
						$paymentMethod =  __('Credit Card');
					} else if ($recharge['Recharge']['payment_method'] == 3) {
						$paymentMethod =  __('Points');
					}

					// Fill rows with data
					$content .=
						$recharge['Recharge']['merchant_txn_id'] . "," .
						$recharge['Operator']['name'] . "," .
						$recharge['Recharge']['phone_number'] . "," .
						$paymentMethod . "," .
						$recharge['Recharge']['amount'] . "," .
						$recharge['Recharge']['recharge_date'] . "," .
						$recharge['Recharge']['points'] . "," .
						$rechargeStatus . "," .
						$recharge['Recharge']['x'] . "," .
						$recharge['Recharge']['y'] .
						"\n";
				}
			}

			// Generate new file
			$path = realpath('../../app/webroot/uploads/') . '/';
			$FileName = 'UserTransactions(UserId ' . base64_decode($_REQUEST['user_id']) . ').csv';
			$NewFile = $path . $FileName;
			file_put_contents($NewFile, $content);
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="' . $FileName . '"');
			readfile($NewFile);
			exit();
		}
	}

	/**
	 * Export a user's purchases
	 */
	public function admin_export_purchases() {
		$this->autoRender = false;

		if ($_REQUEST['user_id']) {
			$Searchdata = json_decode($this->data['User']['data']);
			$sortFields = array(
				'Bank.bank_name',
				'Payment.id',
				'Payment.payment_method',
				'Payment.reference_number',
				'Payment.notification_date',
				'Payment.change_status_date',
				'Payment.amount',
				'Payment.tax',
				'Payment.fees',
				'Payment.amount_credited',
				'Payment.status',
				'Payment.denial_reason',
			);
			$searchVal = $Searchdata->oSearch->sSearch;
			$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];

			if ($sortBy == 'Payment.status') {
				$Sort = ($Searchdata->aaSorting[0][1] == 'desc') ? 'asc' : 'desc';
			} else {
				$Sort = $Searchdata->aaSorting[0][1];
			}
			$conditions = array('user_id' => base64_decode($_REQUEST['user_id']));

			// Set conditions
			if (@$searchVal != '') {
				$conditions[] =
					"(Payment.id LIKE (\"%" . $searchVal .
					"%\") OR Payment.reference_number LIKE (\"%" . $searchVal .
					"%\") OR Payment.amount LIKE (\"%" . $searchVal .
					"%\") OR  DATE_FORMAT(Payment.notification_date,\"%d %b, %Y %h:%i %p\") LIKE (\"%" . $searchVal .
					"%\") OR Payment.amount_credited LIKE (\"%" . $searchVal .
					"%\"))";
			}

			if ($sortBy != '' && $Sort != '') {
				$orderBY = $sortBy . ' ' . $Sort;
			} else {
				$orderBY = 'Payment.id desc';
			}

			// Find data in payments table
			$data = $this->Payment->find(
				'all',
				array(
					'conditions' => $conditions,
					'fields'     => array(
						'Payment.*',
						'Bank.*'
					),
					'order'      => $orderBY,
					'joins'      => array(
						array(
							'table'      => 'banks',
							'alias'      => 'Bank',
							'type'       => 'LEFT',
							'conditions' => array('Payment.bank_id=Bank.id')
						)
					)
				)
			);
			$content = '';

			// Generate column headers
			if (!empty($data)) {
				$content .=
						__("Payment Number,Type,Bank,Account Type,Account Number,Reference,") .
						__("Notification,Status Change,Payment Amount,Tax Deducted,Fees Paid,Balance Added,Status,X,Y") .
						"\n";

				foreach ($data as $payment) {

					// Translate status codes
					if ($payment['Payment']['status'] == 0) {
						$paymentStatus =  __('Pending');
					} else if ($payment['Payment']['status'] == 1) {
						$paymentStatus =  __('Approved');
					} else if ($payment['Payment']['status'] == 2) {
						$paymentStatus =  __('Denied');
					}

					// Declare account type variable
					$accountType = '';

					// Translate payment method
					if ($payment['Payment']['payment_method'] == 1) {
						$method =  __('Bank Deposit');

						// Translate account type
						if ($payment['Bank']['account_type'] == 1) {
							$accountType =  __('Checking');
						} else if ($payment['Bank']['account_type'] == 2) {
							$accountType =  __('Savings');
						} else {
							echo $accountType['Bank']['account_type'];
						}
						} else if ($payment['Payment']['payment_method'] == 2) {
							$method =  __('Credit Card');
						} else {
							echo $payment['Payment']['payment_method'];
						}

					// Fill rows with data
					$content .=
						$payment['Payment']['id'] . "," .
						$method . "," .
						$payment['Bank']['bank_name'] . "," .
						$accountType . "," .
						$payment['Bank']['account_number'] . "," .
						$payment['Payment']['reference_number'] . "," .
						$payment['Payment']['notification_date'] . "," .
						$payment['Payment']['change_status_date'] . "," .
						$payment['Payment']['amount'] . "," .
						$payment['Payment']['tax'] . "," .
						$payment['Payment']['fees'] . "," .
						$payment['Payment']['amount_credited'] . "," .
						$paymentStatus . "," .
						$payment['Payment']['x'] . "," .
						$payment['Payment']['y'] .
						"\n";
				}
			}

			// Generate new file
			$path = realpath('../../app/webroot/uploads/') . '/';
			$FileName = 'UserPurchases(UserId ' . base64_decode($_REQUEST['user_id']) . ').csv';
			$NewFile = $path . $FileName;
			file_put_contents($NewFile, $content);
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="' . $FileName . '"');
			readfile($NewFile);
			exit();
		}
	}

	/**
	 * Export a user's reward history
	 */
	public function admin_export_rewards() {
		$this->autoRender = false;

		if ($_REQUEST['user_id']) {
			$Searchdata = json_decode($this->data['User']['data']);
			$sortFields = array(
				'Reward.reward_value',
				'Redemption.*'
			);
			$searchVal = $Searchdata->oSearch->sSearch;
			$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];

			if ($sortBy == 'Redemption.reward_type') {
				$sortBy = 'Redemption.id';
			} else {
				$Sort = $Searchdata->aaSorting[0][1];
			}
			$conditions = array('user_id' => base64_decode($_REQUEST['user_id']));

			// Set conditions
			if (@$searchVal != '') {
				$conditions[] =
					"(Redemption.redeem_date LIKE (\"%" . $searchVal .
					"%\") OR Redemption.points LIKE (\"%" . $searchVal .
					"%\") )";
			}

			if ($sortBy != '' && $Sort != '') {
				$orderBY = $sortBy . ' ' . $Sort;
			} else {
				$orderBY = 'Redemption.id desc';
			}

			// Get data from redemptions table
			$data = $this->Redemption->find(
				'all',
				array(
					'conditions' => $conditions,
					'fields'     => array(
						'Redemption.*',
						'Reward.*'
					),
					'order'      => $orderBY,
					'joins'      => array(
						array(
							'table'      => 'rewards',
							'alias'      => 'Reward',
							'type'       => 'LEFT',
							'conditions' => array('Redemption.reward_id=Reward.id')
						)
					)
				)
			);
			$content = '';

			// Generate column headers
			if (!empty($data)) {
				$content.=  __("Reward ID,Reward Type,Reward Value,Points Spent,Date & Time") . "\n";

				foreach ($data as $redemption) {

					if ($redemption['Redemption']['reward_type'] == 1) {
						$redeemType = "Recharge";
					} else if ($val['Redemption']['reward_type'] == 2) {
						$redeemType = "Customer Support";
					} else if ($val['Redemption']['reward_type'] == 3) {
						$redeemType = "Download";
					}

					// Fill rows with data
					$content .=
						$redemption['Redemption']['reward_id'] . "," .
						$redeemType . "," .
						$redemption['Reward']['reward_value'] . "," .
						$redemption['Redemption']['points'] . "," .
						$redemption['Redemption']['redeem_date'] . "," .
						"\n";
				}
			}

			// Generate new file
			$path = realpath('../../app/webroot/uploads/') . '/';
			$FileName = 'UserRewards(UserId ' . base64_decode($_REQUEST['user_id']) . ').csv';
			$NewFile = $path . $FileName;
			file_put_contents($NewFile, $content);
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="' . $FileName . '"');
			readfile($NewFile);
			exit();
		}
	}

	/**
	 * Get user by ID
	 */
	public function admin_getUserByID($id) {
		$this->layout = '';

		if (!empty($id)) {
			$user = $this->User->find(
				'first',
				array(
					'conditions' => array('id' => $id)
				)
			);
			return $user;
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', __("Invalid User ID"));
			$this->redirect(
				array(
					'controller' => 'User',
					'action'     => 'index'
				)
			);
		}
	}

	/**
	 * Get users
	 */
	public function getUser() {
		$this->autoRender = false;
		$conditions = array(
			'delete_status' => 0,
			'user_type'     => 1
		);
		$user = $this->User->find(
			'list',
			array(
				'conditions' => $conditions,
				'fields'     => array('name')
			)
		);
		return $user;
	}

	/**
	 * Get a user's name from it's id
	*/
	public function admin_getUserName($id) {
		$this->autoRender = false;

		// If a User ID is specified, find it in the table
		if (!empty($id)) {
			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'id'     => $id
					)
				)
			);

			// And return the name
			return $user;

		// Otherwise return blank
		} else {
			return '';
		}
	}

	/**
	 * Get a staff member's name from it's id
	*/
	public function admin_getStaffName($id) {
		$this->autoRender = false;

		// If a Staff ID is specified, find it in the table
		if (!empty($id)) {
			$staff = $this->Admin->find(
				'first',
				array(
					'conditions' => array(
						'id'     => $id
					)
				)
			);

			// And return the name
			return $staff;

		// Otherwise return blank
		} else {
			return '';
		}
	}

	/**
	 * Count the number of users registered between dates
	 */
	public function total_users() {
		$this->autoRender = false;
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);
		$conditions = array('delete_status' => 0);
		$conditions['user_type'] = 1;

		if ($fromDate != '' && $toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$conditions[] = " DATE(registered_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\"";
		}
		$users = $this->User->find('count', array('conditions' => $conditions));

		return $users;
	}

	/*
	 * Total user balance
	*/
	public function total_user_balance() {
		$data = $this->User->find(
			'all',
			array(
				'fields'     => array('SUM(User.balance) AS total'),
				'conditions' => array('User.user_type' => 1)
			)
		);
		return $data;
	}
}
