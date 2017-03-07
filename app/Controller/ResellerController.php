<?php
/**
 * Reseller Controller
 *
 * This file handles resellers in general. Creation, edition, deletion,
 * profiles and airtime purchases
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

App::uses('CakeEmail', 'Network/Email');

class ResellerController extends AppController {

	var $uses = array(
		'Sponsor',
		'Admin',
		'Reseller',
		'Setting',
		'Recharge',
		'Payment',
		'Transaction',
		'AccountHistory',
		'Payment',
	);

	var $components = array(
		'Validation',
		'ImageUpload'
	);

	/**
	 * List resellers
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

		// Load Reseller model
		$this->loadModel('Reseller');

		// Set conditions
		$data = $this->Reseller->find(
			'all',
			array(
				'conditions' => array(
					'Reseller.delete_status' => 0,
					'Reseller.user_type'     => 2
				),
				'fields'     => array(
					'Reseller.*',
					'Sponsor.name'
				),
				'order'      => 'Reseller.id desc',
				'joins'      => array(
					array(
						'table'      => 'sponsors',
						'alias'      => 'Sponsor',
						'type'       => 'INNER',
						'conditions' => array('Reseller.sponsor_id=Sponsor.id')
					)
				)
			)
		);
		$this->set('userdata', $data);
		$adminData = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $adminData);
	}

	/**
	 * Create new reseller
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
		$checkReseller = 0;

		// Set conditions
		if (!empty($this->request->data)) {
			$exist_Email = $this->Reseller->find(
				'all',
				array(
					'conditions' => array('email' => $this->request->data['Reseller']['email'])
				)
			);

			foreach ($exist_Email as $deleted_user) {

				if ($deleted_user['Reseller']['delete_status'] == '0') {
					$checkReseller = 1;
					break;
				}
			}

			// Validate name
			if ($this->Validation->Presence($this->request->data['Reseller']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a name'));

			// Validate Cedula or Passport
			} else if ($this->Validation->Presence($this->request->data['Reseller']['tax_id'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a Cedula or Passport number'));

			// Validate presence of email address
			} else if ($this->Validation->Presence($this->request->data['Reseller']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an email address'));

			// Validate email address format
			} else if ($this->Validation->Email($this->request->data['Reseller']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid email address'));

			// Validate presence of address
			} else if ($this->Validation->Presence($this->request->data['Reseller']['address'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an address'));

			// Validate presence of phone number
			} else if($this->Validation->Presence($this->request->data['Reseller']['phone_number'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a phone number'));

			// Check if email is already registered
			} else if ($checkReseller == 1) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Email already registered");
				$this->render();
			} else {

				// Set default sponsor
				if ($this->request->data['Reseller']['sponsor_id'] == 0) {
					$this->request->data['Reseller']['sponsor_id'] = 1;
				}

				// Create user
				$data['Reseller'] = $this->request->data['Reseller'];

				// Set registration date and time
				$data['Reseller']['registered'] = date('Y-m-d H:i:s');

				// Auto generate and encrypt password
				$password = $this->Validation->generatePassword();
				$data['Reseller']['password'] = Security::hash($password, 'sha1', true);

				// Set user type
				$data['Reseller']['user_type'] = 2;

				// Set email verification and status to 0
				$data['Reseller']['email_verify'] = 0;
				$data['Reseller']['status'] = 0;
				$setting = $this->Setting->find('first');

				// Check for discount percentage or set to default if blank
				if ($this->request->data['Reseller']['discount_rate'] == 0) {
					$data['Reseller']['discount_rate'] = $setting['Setting']['discount_rate'];
				}

				// Save and Send activation email
				if ($this->Reseller->save($data['Reseller'])) {

					// Generate activation code
					$enc_uid = sha1($this->Reseller->id);

					// Generate activation url
					$activation_url	= Router::url('/home/activate/', true) . $enc_uid;

					// Generate email body
					$msg =
						'<html>
						<body>
							<div style="font-family:Tahoma;">
								Bienvenido, ' . $data['Reseller']['name'] . '<br/><br/>
								Tu cuenta de Club Prepago Empresarios ha sido creda correctamente<br/>
								<span style="font-size:12px;"><b>Usuario: </b> ' . $data['Reseller']['email'] . ' </span><br/>
								<span style="font-size:12px;"><b>Contraseña: </b> ' . $password . ' </span><br/><br/>
								Por favor, <a href=' . $activation_url . '>Haz Click Aquí</a> para activar tu cuenta.<br/><br/>
								Si tienes algún problema, escríbenos a <a href="mailto:soporte@clubprepago.com">soporte@clubprepago.com</a></br>
								o llámanos al <b>+507 388-6220</b><br/><br/>
								Gracias,<br/><br/>
								<b>Club Prepago Celular</b>
							</div>
						</body>
						<html>';

					// Set email headers
					$Email = new CakeEmail();
					$Email->emailFormat('html');
					$Email->config('smtp');
					$Email->to($data['Reseller']['email']);
					$Email->subject('¡Bienvenido a Club Prepago Empresarios!');

					// Send email message
					$Email->send($msg);

					// Generate success message
					$this->Session->write('success', "1");
					$this->Session->write('alert', __("Reseller created successfully"));
					echo "<script>window.location.href='index'</script>";
					exit;

				// If there was a problem, generate error message
				} else	{
					$this->Session->write('success', "0");
					$this->Session->write('alert', __("Error creating Reseller"));
					$this->render();
				}
			}
		}
	}

	/**
	 * View a reseller
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


		// Get reseller's datafrom users table
		if (!empty($this->request->data)) {
			$exist_Email = $this->Reseller->find(
				'all',
				array(
					'conditions' => array(
						'email'         => $this->request->data['Reseller']['email'],
						'id !='         => $this->request->data['Reseller']['id'],
						'delete_status' => 0
					)
				)
			);

			$encoded_id = base64_encode($this->request->data['Reseller']['id']);
			echo "<script>window.location.href='../edit/" . urlencode($encoded_id) . "'</script>";
			exit;

		// Find user by id
		} else {

			if (is_numeric(base64_decode($id))) {
				$this->request->data = $this->Reseller->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);

			// If user could not be found, trigger an error
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid Reseller'));
			}
		}
	}

	/**
	 * Edit reseller
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

		// Set conditions
		if (!empty($this->request->data)) {
			$exist_Email = $this->Reseller->find(
				'all',
				array(
					'conditions' => array(
						'email'         => $this->request->data['Reseller']['email'],
						'id !='         => $this->request->data['Reseller']['id'],
						'delete_status' => 0
					)
				)
			);

			// Validate name
			if ($this->Validation->Presence($this->request->data['Reseller']['name'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a name'));

			// Validate Cedula or Passport
			} else if ($this->Validation->Presence($this->request->data['Reseller']['tax_id'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a Cedula or Passport number'));

			// Validate presence of email address
			} else if ($this->Validation->Presence($this->request->data['Reseller']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an email address'));

			// Validate email address format
			} else if ($this->Validation->Email($this->request->data['Reseller']['email'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid email address'));

			// Check if email already exists
			} else if (!empty($exist_Email)) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Email already registered'));

			// Validate presence of address
			} else if ($this->Validation->Presence($this->request->data['Reseller']['address'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter an address'));

			// Validate presence of phone number
			} else if($this->Validation->Presence($this->request->data['Reseller']['phone_number'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('You must enter a phone number'));

			// Validate Discount
			} else if ($this->Validation->Presence($this->request->data['Reseller']['discount_rate'])) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Discount percentage cannot be blank"));

/*			// If there was an error uploading the image, generate error message
			} else if (@$this->request->data['Reseller']['img']['error'] != 0 ) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Error uploading image'));

			// If the image is too big, generate error message
			} else if ($this->ImageUpload->img_size(@$this->request->data['Reseller']['img']['size']) != 1) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Image Size should be less than 10MB'));
*/			} else {

				if ($this->request->data['Reseller']['sponsor_id'] == 0) {

					// Set default Sponsor if none is specified
					$this->request->data['Reseller']['sponsor_id'] = '1';
				} 
				$data['Reseller'] = $this->request->data['Reseller'];

/*				// Check and upload image
				if (@$this->request->data['Reseller']['img']['error'] == 0 && $this->ImageUpload->img_size(@$this->request->data['Reseller']['img']['size']) == 1) {

					// Set image destination directory
					$destination_med = realpath('../../app/webroot/img/resellers/') . '/';
					$FILE = $this->request->data['Reseller']['img'];
					$ext  = $this->ImageUpload->GetExt($FILE['name']);

					// Set file details
					$imgname = strtotime(date('Y-m-d h:i:s'));
					$imgname = sha1($imgname) . '.' . $ext;
					$this->request->data['Reseller']['image'] = $imgname;

					// Upload image
					$this->ImageUpload->myupload($FILE, $destination_med, $imgname, NULL, NULL, $imgname);
				}
*/
				// Reseller update successful
				if ($this->Reseller->save($this->request->data)) {
					$this->Session->write('success', "1");
					$this->Session->write('alert', __("Reseller updated successfully"));
				} else {

					// Reseller update failed
					$this->Session->write('success', "0");
					$this->Session->write('alert', __("Reseller could not be updated"));
				}
				echo "<script>window.location.href='../index'</script>";
				exit;
			}

			$encoded_id = base64_encode($this->request->data['Reseller']['id']);
			echo "<script>window.location.href='../edit/".urlencode($encoded_id)."'</script>";
			exit;
		} else {

			if (is_numeric(base64_decode($id))) {
				$this->request->data = $this->Reseller->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __("Invalid Reseller"));
			}
		}
	}

	/**
	 * Delete Reseller
	 */
	public function admin_delete($id) {
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);
		$this->autoRender = false;

		if (is_numeric(base64_decode($id))) {
			$data['Reseller']['id'] = base64_decode($id);
			$data['Reseller']['delete_status'] = '1';

			if($this->Reseller->save($data)) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', __("Reseller deleted successfully"));
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

		// If a reseller id is received
		if (!empty($this->request->data)) {

			// Start assembling update query
			$qry = 'UPDATE users SET ';

			// Decode reseller id and store in array
			$resellerInfo = $this->Reseller->find(
				'first',
				array(
					'conditions' => array(
						'id' => base64_decode($id)
					),
					'order'      => 'id desc'
				)
			);

			if ($this->request->data['Reseller']['name'] == 1) {

				// If you try to extract too much balance
				if ($resellerInfo['Reseller']['balance'] < $this->request->data['Reseller']['amount'] &&
					$this->request->data['Reseller']['action'] == 2) {

						// Display error message
						$this->Session->write('success', "0");
						$this->Session->write('alert', __("You can't extract an amount higher than the current balance of the account"));

						// Redirect back to Account
						$this->redirect(
							array(
								'controller' => 'reseller',
								'action'     => 'account',
								$id
							)
						);
				}

				// Continue assembling update query
				$qry .= ' balance = balance ';

				// Get update amount from array
				$amount = $this->request->data['Reseller']['amount'];
			} else if ($this->request->data['Reseller']['name'] == 2) {

				// If you try to extract too many points
				if ($resellerInfo['Reseller']['points'] < $this->request->data['Reseller']['amount'] &&
					$this->request->data['Reseller']['action'] == 2) {

						// Display error message
						$this->Session->write('success', "0");
						$this->Session->write('alert', __("You can't extract more points than are available in the account"));

						// Redirect back to Account
						$this->redirect(
							array(
								'controller' => 'reseller',
								'action'     => 'account',
								$id
							)
						);
				}

				// Continue assembling update query
				$qry .= ' points=points ';

				// Get amount from array
				$amount = $this->request->data['Reseller']['amount'];
			}

			// If adding balance
			if ($this->request->data['Reseller']['action'] == 1) {
				$qry .= ' + ';
				$action = '+';

			// If subtracting balance
			} else if ($this->request->data['Reseller']['action'] == 2) {
				$qry .= ' - ';
				$action = '-';
			}

			// Continue assembling update query
			$qry .= $amount . ' WHERE id=\'' . base64_decode($id) . '\' ';

			// Excecute update query on users table
			$data = $this->Reseller->query($qry);

			// Gather data for account_history table update
			$history = array(
				'AccountHistory' => array(
					'detail'       => $this->request->data['Reseller']['detail'],
					'amount'       => $action . $amount,
					'user_id'      => base64_decode($id),
					'account_type' => $this->request->data['Reseller']['name'],
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
			$this->request->data = $this->Reseller->find(
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
				$this->request->data = $this->Reseller->find(
					'first',
					array(
						'conditions' => array('id' => base64_decode($id))
					)
				);

			// If no user or an invalid user is specified
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Invalid Reseller'));
			}
		}
	}

	/**
	 * View reseller sales history
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

		// Check current user
		$admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $admindata);

		// If a user ID is specified
		if (is_numeric(base64_decode($id))) {

			// Find data in recharges table
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

			// Save results to userdata
			$this->set('userdata', $data);
		}
	}

	/**
	 * View reseller airtime purchases
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
	 * Export reseller list
	 */
	public function admin_export() {
		$this->autoRender = false;
		$Searchdata = json_decode($this->data['Reseller']['data']);
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
			'user_type'     => 2
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

		// Get data from resellers table
		$data = $this->Reseller->find(
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

			foreach ($data as $reseller) {

				// Translate country code
				if ($reseller['Reseller']['country_id'] == 1) {
					$country = 'Panama';
				} else {
					$country = $reseller['Reseller']['country_id'];
				}

				// Translate status code
				if ($reseller['Reseller']['status'] == 0) {
					$status = __('Inactive');
				} else if ($reseller['Reseller']['status'] == 1) {
					$status = __('Active');
				} else {
					$status = $reseller['Reseller']['status'];
				}

				// Translate email verify code
				if ($reseller['Reseller']['email_verify'] == 0) {
					$email_verify = __('No');
				} else if ($reseller['Reseller']['email_verify'] == 1) {
					$email_verify = __('Yes');
				} else {
					$email_verify = $reseller['Reseller']['email_verify'];
				}

				// Translate banned code
				if ($reseller['Reseller']['banned'] == 0) {
					$banned = __('No');
				} else if ($reseller['Reseller']['banned'] == 1) {
					$banned = __('Yes');
				} else {
					$banned = $reseller['Reseller']['banned'];
				}

				$content .=
					$reseller['Reseller']['id'] . "," .
					$reseller['Reseller']['name'] . "," .
					$reseller['Reseller']['tax_id'] . "," .
					$reseller['Reseller']['email'] . "," .
					"\"" . $reseller['Reseller']['address'] . "\"" . "," .
					$reseller['Reseller']['city'] . "," .
					$reseller['Reseller']['state'] . "," .
					$country . "," .
					$reseller['Reseller']['phone_number'] . "," .
					$reseller['Reseller']['registered'] . "," .
					$status . "," .
					$email_verify . "," .
					$banned . "," .
					"\n";
			}
		}
		$path = realpath('../../app/webroot/uploads/') . '/';
		$FileName = 'Resellers.csv';
		$NewFile = $path . $FileName;
		file_put_contents($NewFile, $content);
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $FileName . '"');
		readfile($NewFile);
		exit();
	}

	/**
	 * Export a reseller's transactions
	 */
	public function admin_export_transactions() {
		$this->autoRender = false;

		if ($_REQUEST['user_id']) {
			$Searchdata = json_decode($this->data['Reseller']['data']);
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
					__("Transaction ID,Mobile Operator,Phone Number,Amount,Date & Time,Status,X,Y") .
					"\n";

				foreach ($data as $recharge) {

					// Generate names for status codes
					if ($recharge['Recharge']['status'] == 0) {
						$rechargeStatus =  __('Failed');
					} else if ($recharge['Recharge']['status'] == 1) {
						$rechargeStatus =  __('Successful');
					} else if ($recharge['Recharge']['status'] == 2) {
						$rechargeStatus =  __('Replaced');
					}

					// Fill rows with data
					$content .=
						$recharge['Recharge']['merchant_txn_id'] . "," .
						$recharge['Operator']['name'] . "," .
						$recharge['Recharge']['phone_number'] . "," .
						$recharge['Recharge']['amount'] . "," .
						$recharge['Recharge']['recharge_date'] . "," .
						$rechargeStatus . "," .
						$recharge['Recharge']['x'] . "," .
						$recharge['Recharge']['y'] .
						"\n";
				}
			}

			// Generate new file
			$path = realpath('../../app/webroot/uploads/') . '/';
			$FileName = 'ResellerTransactions(UserId ' . base64_decode($_REQUEST['user_id']) . ').csv';
			$NewFile = $path . $FileName;
			file_put_contents($NewFile, $content);
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="' . $FileName . '"');
			readfile($NewFile);
			exit();
		}
	}

	/**
	 * Export a reseller's purchases
	 */
	public function admin_export_purchases() {
		$this->autoRender = false;

		if ($_REQUEST['user_id']) {
			$Searchdata = json_decode($this->data['Reseller']['data']);
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
						"Payment Number,Type,Bank,Account Type,Account Number,Reference," .
						"Notification,Status Change,Payment Amount,Tax Deducted,Fees Paid,Balance Added,Status,X,Y" .
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
			$FileName = 'ResellerPurchases(UserId ' . base64_decode($_REQUEST['user_id']) . ').csv';
			$NewFile = $path . $FileName;
			file_put_contents($NewFile, $content);
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="' . $FileName . '"');
			readfile($NewFile);
			exit();
		}
	}

	/**
	 * Get reseller data by ID
	 */
	public function admin_getResellerByID($id) {
		$this->layout = '';

		if (!empty($id)) {
			$user = $this->Reseller->find(
				'first',
				array(
					'conditions' => array('id' => $id)
				)
			);
			return $user;
		} else {
			$this->Session->write('success', "0");
			$this->Session->write('alert', "Invalid Reseller ID");
			$this->redirect(
				array(
					'controller' => 'Reseller',
					'action'     => 'index'
				)
			);
		}
	}

	/**
	 * Get reseller name
	 */
	public function getReseller($sponsor = null) {
		$this->autoRender = false ;

		if ($sponsor != '') {
			$conditions = array(
				'sponsor_id'    => $sponsor,
				'user_type'     => 2,
				'delete_status' => 0
			);
		} else {
			$conditions = array(
				'delete_status' => 0,
				'user_type'     => 2
			);
		}
		$reseller = $this->Reseller->find(
			'list',
			array(
				'conditions' => $conditions,
				'fields'     => array('name')
			)
		);
		return $reseller;
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
	 * Count the number of resellers
	 */
	public function total_resellers() {
		$data = $this->Reseller->find(
			'count',
			array(
				'conditions' => array(
					'delete_status' => 0,
					'user_type'     => 2
				)
			)
		);
		return $data;
	}

	/*
	 * Total reseller balance
	*/
	public function total_reseller_balance() {
		$data = $this->Reseller->find(
			'all',
			array(
				'fields'     => array('SUM(Reseller.balance) AS total'),
				'conditions' => array('Reseller.user_type' => 2)
			)
		);
		return $data;
	}
}
