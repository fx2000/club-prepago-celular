<?php
/**
 * Report Controller
 *
 * This file handles all report generation and exporting
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class ReportController extends AppController {
	
	var $uses = array(
		'Recharge',
		'ResellerRecharge',
		'Redemption',
		'Operator',
		'Admin',
		'Payment',
		'Bank'
	);
	
	var $components = array('Validation');

	/**
	 * Direct Sales report
	 */
	function admin_direct_sales() {

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
		$Operatordata = $this->Operator->find(
			'list',
			array(
				'fields' => array('name')
			)
		);
		$this->set('Operatordata', $Operatordata);

		// Set conditions array
		$condition = array(
			'Recharge.status'        => 1,
			'Recharge.user_type'     => 1,
			'Recharge.payment_method' => array(1, 2)
		);

		// Get to and from date
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);

		// Get mobile operator
		$operator = @$_REQUEST['operator'];
		
		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'         => 1,
					'Recharge.user_type'      => 1,
					'Recharge.payment_method' => array(1, 2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
				);
		}
		
		// If a mobile operator is specified, add it to conditions array
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}
		
		// If a payment method is specified, add it to conditions array
		if (@$_REQUEST['payment_method'] != '') {
			$condition['Recharge.payment_method'] = $_REQUEST['payment_method'];
		}

		// Get data from recharges table
		$userdata = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array(
					'User.name',
					'User.id',
					'User.delete_status',
					'Operator.name',
					'Recharge.*'
				),
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
		$this->set('userdata', $userdata);
	}

	/**
	 * Export user sales report
	 */
	public function admin_export_direct_sales() {
		$this->autoRender = false;
		$Searchdata = json_decode($this->data['Report']['data']);
		$searchVal = $Searchdata->oSearch->sSearch;
		$sortFields = array(
			'User.name',
			'Operator.name',
			'Recharge.phone_number',
			'Recharge.amount',
			'Recharge.tax_amount',
			'Recharge.total_amount',
			'Recharge.recharge_date'
		);
		$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];
		$Sort = $Searchdata->aaSorting[0][1];
		
		if ($sortBy != '' && $Sort != '') {
			$orderBY = $sortBy . ' ' . $Sort;
		}
		else {
			$orderBY = 'Recharge.recharge_date asc';
		}

		// Set conditions
		$condition = array(
			'Recharge.status' => 1,
			'Recharge.payment_method' => array(1, 2)
		);

		// Set to and from date
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);

		// Set ´mobile operator
		$operator = @$_REQUEST['operator'];
		
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'        => 1,
					'Recharge.payment_method' => array(1, 2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\"");
		}
		
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}
		
		if (@$_REQUEST['payment_method'] != '') {
			$condition['Recharge.payment_method'] = $_REQUEST['payment_method'];
		}
		
		if (@$searchVal != '') {
			$condition[] = 
				"(User.name LIKE (\"%" .
					$searchVal .
					"%\") OR Operator.name LIKE (\"%" .
					$searchVal . "%\") OR Recharge.mobile_no LIKE (\"%" .
					$searchVal . "%\") OR Recharge.amount LIKE (\"%" .
					$searchVal . "%\") OR Recharge.tax_amount LIKE (\"%" .
					$searchVal . "%\") OR Recharge.total_amount LIKE (\"%" .
					$searchVal . "%\") OR  DATE_FORMAT(recharge_date,\"%d %b, %Y\") LIKE (\"%" .
					$searchVal . "%\"))";
		}

		// Set user type
		$condition['Recharge.user_type'] = 1;

		// Find data in recharges table
		$data = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array(
					'User.name',
					'User.id',
					'User.delete_status',
					'Operator.name',
					'Recharge.*'
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
				__("User,Mobile Operator,Phone Number,Payment Method,Recharge Amount,") .
				__("Taxes,Total Amount,Points Awarded,Date & Time,X,Y") .
				"\n";

			// Fill rows with data
			foreach($data as $sale) {

				// Translate Payment method
				if ($sale['Recharge']['payment_method'] == 1) {
					$paymentMethod =  __('Prepaid Balance');
				} else if ($sale['Recharge']['payment_method'] == 2) {
					$paymentMethod =  __('Credit Card');
				} else if ($sale['Recharge']['payment_method'] == 3) {
					$paymentMethod =  __('Points');
				}

				$content .=
					$sale['User']['name'] . "," .
					$sale['Operator']['name'] . "," .
					$sale['Recharge']['phone_number'] . "," .
					$paymentMethod . "," .
					$sale['Recharge']['amount'] . "," .
					$sale['Recharge']['tax_amount'] . "," .
					$sale['Recharge']['total_amount'] . "," .
					$sale['Recharge']['points'] . "," .
					$sale['Recharge']['recharge_date'] . "," .
					$sale['Recharge']['x'] . "," .
					$sale['Recharge']['y'] .
					"\n";
			}
		}

		// Generate new file
		$path = realpath('../../app/webroot/uploads/') . '/';
		$FileName = 'DirectSales.csv';
		$NewFile = $path . $FileName;
		file_put_contents($NewFile, $content);
		header('Content-Type: application/csv'); 
		header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
		readfile($NewFile);
		exit();	
	}

	/**
	 * Reseller Sales report
	 */
	function admin_reseller_sales() {

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
		$Operatordata = $this->Operator->find(
			'list',
			array(
				'fields' => array('name')
			)
		);
		$this->set('Operatordata', $Operatordata);

		// Set conditions array
		$condition = array(
			'Recharge.status'        => 1,
			'Recharge.user_type'     => 2,
			'Recharge.payment_method' => array(1, 2)
		);

		// Get to and from date
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);

		// Get sponsor
		$sponsor = @$_REQUEST['sponsor'];

		// Get reseller
		$reseller = @$_REQUEST['reseller'];

		// Get mobile operator
		$operator = @$_REQUEST['operator'];

		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'        => 1,
					'Recharge.payment_method' => array(1,2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate."\"");
		}

		// If a mobile operator is specified, add it to conditions array
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}

		// If a sponsor is specified, add it to conditions array
		if ($sponsor != '') {
			$condition['Sponsor.id'] = $sponsor;
		}

		// If a reseller is specified, add it to conditions array
		if ($reseller != '') {
			$condition['Reseller.id'] = $reseller; 
		}

		// Get data from recharges table
		$userdata = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array(
					'Reseller.name',
					'Reseller.id',
					'Reseller.delete_status',
					'Sponsor.name',
					'Sponsor.id',
					'Sponsor.delete_status',
					'Operator.name',
					'Recharge.*'
				),
				'joins'      => array(
					array(
						'table'      => 'operators',
						'alias'      => 'Operator',
						'type'       => 'INNER',
						'conditions' => array('Recharge.operator=Operator.id')
					),
					array(
						'table'=>'users',
						'alias'=>'Reseller',
						'type'=>'INNER',
						'conditions'=>array('Recharge.user_id=Reseller.id')
					),
					array(
						'table'      => 'sponsors',
						'alias'      => 'Sponsor',
						'type'       => 'INNER',
						'conditions' => array('Reseller.sponsor_id=Sponsor.id')
					)
				)
			)
		);
		$this->set('userdata', $userdata);
	}

	/**
	 * Export reseller sales report
	 */
	public function admin_export_reseller_sales() {
		$this->autoRender = false;
		$Searchdata = json_decode($this->data['Report']['data']);
		$searchVal = $Searchdata->oSearch->sSearch;
		$sortFields = array(
			'Sponsor.name',
			'Retailer.name',
			'Operator.name',
			'ResellerRecharge.phone_number',
			'ResellerRecharge.amount',
			'ResellerRecharge.tax_amount',
			'ResellerRecharge.total_amount',
			'ResellerRecharge.recharge_date'
		);
		$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];
		$Sort = $Searchdata->aaSorting[0][1];
		
		if ($sortBy != '' && $Sort != '') {
			$orderBY= $sortBy.' '.$Sort;
		}
		else {
			$orderBY = 'Recharge.id desc';
		}

		// Set conditions
		$condition = array(
			'Recharge.status'        =>1,
			'Recharge.payment_method' => array(1, 2)
		);

		// Set to and from date
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);

		// Set sponsor
		$sponsor = @$_REQUEST['sponsor'];

		// Set reseller
		$reseller = @$_REQUEST['reseller'];

		// Set mobile operator
		$operator = @$_REQUEST['operator'];
		
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
				'Recharge.status'        => 1,
				'Recharge.payment_method' => array(1, 2),
				"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
			);
		}
		
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}
		
		if ($sponsor != '') {
			$condition['Sponsor.id'] = $sponsor;
		}
		
		if ($reseller != '') {
			$condition['Reseller.id'] = $reseller;
		}

		// Set user type
		$condition['Recharge.user_type'] = 2;

		// Get data from recharges table
		$data = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array(
					'Reseller.name',
					'Reseller.id',
					'Reseller.delete_status',
					'Sponsor.name',
					'Sponsor.id',
					'Sponsor.delete_status',
					'Operator.name',
					'Recharge.*'
				),
				'joins'       => array(
					array(
						'table'      => 'operators',
						'alias'      => 'Operator',
						'type'       => 'INNER',
						'conditions' => array('Recharge.operator=Operator.id')
					),
					array(
						'table'      => 'users',
						'alias'      => 'Reseller',
						'type'       => 'INNER',
						'conditions' => array('Recharge.user_id=Reseller.id')
					),
					array(
						'table'      => 'sponsors',
						'alias'      => 'Sponsor',
						'type'       => 'INNER',
						'conditions' => array('Reseller.sponsor_id=Sponsor.id')
					)
				)
			)
		);
		$content = '';
		
		// Generate column headers
		if (!empty($data)) {
			$content .= "Sponsor,Reseller,Mobile Operator,Phone Number,Amount,Date & Time,X,Y" . "\n";
	
			// Fill rows with data		
			foreach($data As $sale) {
				
				$content .= 
					$sale['Sponsor']['name'] . "," .
					$sale['Reseller']['name'] . "," .
					$sale['Operator']['name'] . "," .
					$sale['Recharge']['phone_number'] . "," .
					$sale['Recharge']['amount'] . "," .
					$sale['Recharge']['recharge_date'] . "," .
					$sale['Recharge']['x'] . "," .
					$sale['Recharge']['y'] .
					"\n";
			}
		}

		// Generate new file
		$path = realpath('../../app/webroot/uploads/') . '/';
		$FileName = 'ResellerSales.csv';
		$NewFile = $path . $FileName;
		file_put_contents($NewFile,$content);
		header('Content-Type: application/csv'); 
		header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
		readfile($NewFile);
		exit();
	}

	/**
	 * Transactions report
	 */
	public function admin_transactions() {

		// Check that session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		$inputDate = @$_REQUEST['input_date'];
		$inputDateArr = explode('-', $inputDate);

		// Create conditions array
		$conditions = array();
		
		// If a date is entered, add it to the conditions array
		if ($inputDate != ''&& checkdate($inputDateArr[1], $inputDateArr[2], $inputDateArr[0])) {
			$conditions = array("DATE(Recharge.recharge_date)"=>$inputDate);
		}

		// If a payment method is entered, add it to the conditions array
		if (@$_REQUEST['payment_method'] != '') {
			$conditions[] = "payment_method=\"" . $_REQUEST['payment_method'] . "\"";
		}

		// If a user is entered, add it to the conditions array
		if (@$_REQUEST['username'] != '') {
			$conditions['User.id'] = $_REQUEST['username'];
		}

		// If a reseller is entered, add it to the conditions array
		if (@$_REQUEST['reseller'] != '') {
			$conditions['User.id'] = $_REQUEST['reseller'];
		}

		// If a status is entered, add it to the conditions array
		if (@$_REQUEST['status'] != '') {
			$conditions['Recharge.status'] = $_REQUEST['status'];
		}

		// Get data from recharges table
		$data = $this->Recharge->find(
			'all',
			array(
				'conditions' => $conditions,
				'fields'     => array(
					'Recharge.*',
					'Operator.name',
					'User.name'
				),
				'order'      => 'id desc',
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
		$this->set('userdata', $data);

		// Get current staff member details
		$Admindata = $this->Admin->find(
			'first',
			array(
				'conditions' => array('id' => $this->Session->read('admin_id'))
			)
		);
		$this->set('Admindata', $Admindata);
	}

	/**
	 * Export transactions report
	 */
	public function admin_export_transactions() { 
		
		$this->autoRender = false;
		$Searchdata = json_decode($this->data['Report']['data']);
		$searchVal = $Searchdata->oSearch->sSearch;
		$sortFields = array(
			'Recharge.transaction_id',
			'User.name',
			'Operator.name',
			'Recharge.phone_number',
			'Recharge.amount',
			'Recharge.recharge_date',
			'Recharge.points',
			'Recharge.status'
		);
		$sortBy = @$sortFields[$Searchdata->aaSorting[0][0]];
		
		if ($sortBy == 'Recharge.status') {
			$Sort = ($Searchdata->aaSorting[0][1] == 'desc') ? 'asc' : 'desc';
		} else {
			$Sort = $Searchdata->aaSorting[0][1];
		}
		if ($sortBy != '' && $Sort != '')
			$orderBY = $sortBy . ' ' . $Sort;
		else {
			$orderBY = 'Recharge.id desc';
		}
		$condition = array();

		// Set conditions
		if(@$searchVal!='') {
			$condition[] =
				"(Recharge.transaction_id LIKE (\"%" .
				$searchVal . "%\") OR User.name LIKE (\"%" .
				$searchVal . "%\") OR Operator.name LIKE (\"%" .
				$searchVal . "%\") OR Recharge.mobile_no LIKE (\"%" .
				$searchVal . "%\") OR Recharge.amount LIKE (\"%" .
				$searchVal . "%\") OR  DATE_FORMAT(Recharge.recharge_date,\"%d %b, %Y %h:%i %p\") LIKE (\"%" .
				$searchVal . "%\") OR Recharge.points LIKE (\"%" .
				$searchVal . "%\"))";
		}
		$inputDate = @$_REQUEST['input_date'];
		$inputDateArr = explode('-', $inputDate);
		
		// If a date is specified
		if ($inputDate != '' && checkdate($inputDateArr[1], $inputDateArr[2], $inputDateArr[0])) {
			$condition[] = "DATE(Recharge.recharge_date)=\"" . $inputDate . "\"";
		}
		
		// If a payment method is specified
		if (@$_REQUEST['payment_method'] != ''){
			$condition[] = "payment_method=\"" . $_REQUEST['payment_method'] . "\"";
		}
		
		// If a user is specified
		if (@$_REQUEST['username'] != ''){
			$condition['User.id'] = $_REQUEST['username'];
		}
		
		// If a reseller is specified
		if (@$_REQUEST['reseller'] != ''){
			$condition['User.id'] = $_REQUEST['reseller'];
		}
		
		// If a status is specified
		if (@$_REQUEST['status'] != ''){
			$condition['Recharge.status'] = $_REQUEST['status'];
		}

		// Get data from recharges table
		$data = $this->Recharge->find(
			'all',
			array(
				'fields'     => array(
					'Recharge.*',
					'Operator.name',
					'User.name'
				),
				'conditions' => $condition,
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
				__("Transaction ID,User,Reseller,Mobile Operator,Phone Number,") .
				__("Amount,Payment Method,Date & Time,Points Awarded,Status,Replaced By,X,Y") .
				"\n";
			
			// Fill rows with data
			foreach ($data as $recharge) {
				
				// Translate status code
				if ($recharge['Recharge']['status'] == 0){
					$rechargeStatus = __('Failed');
				} else if ($recharge['Recharge']['status'] == 1) {
					$rechargeStatus = __('Successful');
				} else if ($recharge['Recharge']['status'] == 2) {
					$rechargeStatus = __('Replaced');
				}
				
				// Translate payment method
				if ($recharge['Recharge']['payment_method'] == 1) {
					$paymentMethod = __("Prepaid Balance");
				} else if ($recharge['Recharge']['payment_method'] == 2) {
					$paymentMethod = __("Credit Card");
				} else if ($recharge['Recharge']['payment_method'] == 3) {
					$paymentMethod = __("Reward Points");
				} else {
					$paymentMethod = $recharge['Recharge']['payment_method'];
				}
				
				if ($recharge['Recharge']['user_type'] == 1) {
					$content .=
						$recharge['Recharge']['merchant_txn_id'] . "," .
						$recharge['User']['name'] .
						",," .
						$recharge['Operator']['name'] . "," .
						$recharge['Recharge']['phone_number'] . "," .
						$recharge['Recharge']['amount'] . "," .
						$paymentMethod . "," .
						$recharge['Recharge']['recharge_date'] . "," .
						$recharge['Recharge']['points'] . "," .
						$rechargeStatus . "," .
						$recharge['Recharge']['replaced_by'] . "," .
						$recharge['Recharge']['x'] . "," .
						$recharge['Recharge']['y'] .
						"\n";
				} else {
					$content .=
						$recharge['Recharge']['merchant_txn_id'] .
						",," .
						$recharge['User']['name'] . "," .
						$recharge['Operator']['name'] . "," .
						$recharge['Recharge']['phone_number'] . "," .
						$recharge['Recharge']['amount'] . "," .
						$paymentMethod . "," .
						$recharge['Recharge']['recharge_date'] . "," .
						$recharge['Recharge']['points'] . "," .
						$rechargeStatus . "," .
						$recharge['Recharge']['replaced_by'] . "," .
						$recharge['Recharge']['x'] . "," .
						$recharge['Recharge']['y'] .
						"\n";
				}
			}
		}

		// Generate new file
		$path = realpath('../../app/webroot/uploads/') . '/';
		$FileName = 'Transactions.csv';
		$NewFile = $path . $FileName;
		file_put_contents($NewFile,$content);
		header('Content-Type: application/csv'); 
		header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
		readfile($NewFile);
		exit();	
	}

	/**
	 * User airtime purchase history report
	 */
	function admin_user_purchases() {

		// Check that session is valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);

		// Load standard layout
		$this->layout = 'admin_layout';

		// Set conditions array
		$condition = array(
			'Payment.status'         => 1,
			'Payment.payment_method' => array(1, 2)
		);

		// Get to and from date
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);

		// Get user
		$user = @$_REQUEST['user'];

		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					"DATE(change_status_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\"");
		}

		// If a mobile operator is specified, add it to conditions array
		if ($user != '') {
			$condition['Payment.user_id'] = $user;
		}

		// Find data
		$userdata = $this->Payment->find(
			'all',
			array(
				'conditions' => array(
					'Payment.status'    => 1,
					'Payment.user_type' => 1
				),
				'fields'     => array(
					'User.name',
					'User.id',
					'User.delete_status',
					'Payment.*'
				),
				'joins'      => array(
					array(
						'table'      => 'users',
						'alias'      => 'User',
						'type'       => 'INNER',
						'conditions' => array('Payment.user_id=User.id')
					)
				)
			)
		);
		$this->set('userdata', $userdata);
	}

	/**
	 * Export user purchases
	 */
	public function admin_export_user_purchases() {
		$this->autoRender = false;
		$Searchdata = json_decode($this->data['Report']['data']);
		$searchVal = $Searchdata->oSearch->sSearch;
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
		$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];
		$Sort = $Searchdata->aaSorting[0][1];
		
		if ($sortBy != '' && $Sort != '') {
			$orderBY= $sortBy.' '.$Sort;
		}
		else {
			$orderBY = 'Recharge.id desc';
		}

		// Set conditions
		$condition = array(
			'Payment.status' =>1,
		);

		// Set to and from date
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);

		// Set mobile user
		$user = @$_REQUEST['user'];
		
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
				'Recharge.status'        => 1,
				'Recharge.payment_method' => array(1, 2),
				"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
			);
		}
		
		if ($user != '') {
			$condition['User.id'] = $user;
		}

		// Find data in payments table
		$data = $this->Payment->find(
			'all',
			array(
				'conditions' => $conditions,
				'fields'     => array(
					'Payment.*',
					'Bank.*',
					'User.*'
				),
				'order'      => $orderBY,
				'joins'      => array(
					array(
						'table'      => 'banks',
						'alias'      => 'Bank',
						'type'       => 'LEFT',
						'conditions' => array('Payment.bank_id=Bank.id')
					),
					array(
						'table'      => 'users',
						'alias'      => 'Reseller',
						'type'       => 'INNER',
						'conditions' => array('Payment.user_id=User.id')
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
		$FileName = 'UserPurchases.csv';
		$NewFile = $path . $FileName;
		file_put_contents($NewFile, $content);
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $FileName . '"');
		readfile($NewFile);
		exit();
	}

	/**
	 * Reseller airtime purchase history report
	 */
	function admin_reseller_purchases() {
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);
		$this->layout = 'admin_layout';
		$Operatordata = $this->Operator->find(
			'list',
			array(
				'fields' => array('name')
			)
		);

		$sponsor = @$_REQUEST['sponsor'];
		$reseller = @$_REQUEST['reseller'];
		
		if ($sponsor != '') {
			$condition['Sponsor.id'] = $sponsor;
		}
		
		if ($reseller != '') {
			$condition['Reseller.id'] = $reseller; 
		}
		$userdata = $this->Payment->find(
			'all',
			array(
				'conditions' => array('Payment.status' => 1, 'Payment.user_type' => 2),
				'fields'     => array(
					'Reseller.name',
					'Reseller.id',
					'Reseller.delete_status',
					'Sponsor.name',
					'Sponsor.id',
					'Sponsor.delete_status',
					'Payment.*'
				),
				'joins'      => array(
					array(
						'table'      => 'users',
						'alias'      => 'Reseller',
						'type'       => 'INNER',
						'conditions' => array('Payment.user_id=Reseller.id')
					),
					array(
						'table'      => 'sponsors',
						'alias'      => 'Sponsor',
						'type'       => 'INNER',
						'conditions' => array('Reseller.sponsor_id=Sponsor.id')
					)
				)
			)
		);
		$this->set('userdata', $userdata);
	}

	/**
	 * Export reseller purchases
	 */
	public function admin_export_reseller_purchases() {
		$this->autoRender = false;
		$Searchdata = json_decode($this->data['Report']['data']);
		$searchVal = $Searchdata->oSearch->sSearch;
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
		$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];
		$Sort = $Searchdata->aaSorting[0][1];
		
		if ($sortBy != '' && $Sort != '') {
			$orderBY= $sortBy.' '.$Sort;
		}
		else {
			$orderBY = 'Recharge.id desc';
		}

		// Set conditions
		$condition = array(
			'Payment.status' =>1,
		);

		// Set to and from date
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);

		// Set mobile user
		$reseller = @$_REQUEST['reseller'];
		
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
				'Recharge.status'        => 1,
				'Recharge.payment_method' => array(1, 2),
				"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
			);
		}
		
		if ($user != '') {
			$condition['User.id'] = $user;
		}

		// Find data in payments table
		$data = $this->Payment->find(
			'all',
			array(
				'conditions' => $conditions,
				'fields'     => array(
					'Payment.*',
					'Bank.*',
					'Reseller.*'
				),
				'order'      => $orderBY,
				'joins'      => array(
					array(
						'table'      => 'banks',
						'alias'      => 'Bank',
						'type'       => 'LEFT',
						'conditions' => array('Payment.bank_id=Bank.id')
					),
					array(
						'table'      => 'users',
						'alias'      => 'Reseller',
						'type'       => 'INNER',
						'conditions' => array('Payment.user_id=Reseller.id')
					)
				)
			)
		);
		$content = '';
		
		// Generate column headers
		if (!empty($data)) {
			$content .=
					__("Payment Number,Type,Bank,Account Type,Account Number,Reference,") .
					__("Notification,Status Change,Payment Amount,Tax Deducted,Fees Paid,Discount Credit,Balance Added,Status,X,Y") .
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
					$payment['Payment']['discount'] . "," .
					$payment['Payment']['amount_credited'] . "," .
					$paymentStatus . "," .
					$payment['Payment']['x'] . "," .
					$payment['Payment']['y'] .
					"\n";
			}
		}

		// Generate new file
		$path = realpath('../../app/webroot/uploads/') . '/';
		$FileName = 'ResellerPurchases.csv';
		$NewFile = $path . $FileName;
		file_put_contents($NewFile, $content);
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $FileName . '"');
		readfile($NewFile);
		exit();
	}

	/**
	 * What does this function do?
	 */
	public function admin_recharge_history() {
		$this->requestAction(array(
			'controller' => 'cpanel',
			'action'     => 'admin_checkSession'));
		$this->layout = 'admin_layout';
		$todayDate =  date('Y-m-d');
		
		if ($this->params['pass'][1] == 2) {
			$fromDate = @$this->params['pass'][2];
			$fromDateArr = explode('-', $fromDate);
			$toDate = @$this->params['pass'][3];
			$toDateArr = explode('-', $toDate);
			
			if ($fromDate!='' &&
				$toDate!='' &&
				checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
				checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
					$conditions = array(
						'Recharge.status'   => 1,
						'Recharge.operator' => $this->params['pass'][0],
						"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
					);
			} else {
				$conditions = array(
					'Recharge.status'   => 1,
					'Recharge.operator' => $this->params['pass'][0]
				);
			}
		} else if ($this->params['pass'][1] == 1) {
			$conditions = array(
				'Recharge.status'         => 1,
				'Recharge.operator'       => $this->params['pass'][0],
				'DATE(Recharge.recharge_date)' => $todayDate
			);
		}
		$userdata = $this->Recharge->find(
			'all',
			array(
				'conditions' => $conditions,
				'fields'     => array(
					'User.name',
					'Recharge.*'
				),
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
		$this->set('userdata', $userdata);
	}

	/**
	 * Check points redeemed per user
	 */
	public function admin_rewards() {
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);
		$this->layout = 'admin_layout';
		$condition = array();
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);
		
		if ($fromDate != '' && $toDate != '' && checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) && checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
			$condition = array("DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\"");
		}
		$userdata = $this->Redemption->find(
			'all',
			array(
				'fields'     => array(
					'User.name',
					'Redemption.*'
				),
				'conditions' => $condition,
				'joins'      => array(
					array(
							'table'      => 'users',
							'alias'      => 'User',
							'type'       => 'INNER',
							'conditions' => array('Redemption.user_id=User.id')
					)
				)
			)
		);
		$this->set('userdata', $userdata);
	}

	/**
	 * Export points redeemed
	 */
	public function admin_export_rewards() {
		$this->autoRender = false;
		$Searchdata = json_decode($this->data['Report']['data']);
		$searchVal = $Searchdata->oSearch->sSearch;
		$sortFields = array(
			'User.name',
			'Redemption.redeem_for ',
			'Redemption.point',
			'Redemption.redemption_code',
			'Redemption.recharge_date'
		);
		$sortBy = $sortFields[$Searchdata->aaSorting[0][0]];
		$Sort = $Searchdata->aaSorting[0][1];
		
		if ($sortBy != '' && $Sort != '') {
			$orderBY = $sortBy . ' ' . $Sort;
		} else {
			$orderBY = 'Redemption.id desc';
		}
		$condition = array();
		$fromDate = @$_REQUEST['from_date'];
		$fromDateArr = explode('-', $fromDate);
		$toDate = @$_REQUEST['to_date'];
		$toDateArr = explode('-', $toDate);
		if ($fromDate != '' && $toDate != '' && checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) && checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
			$condition = array("DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\"");
		}

		// Find data for the file
		if(@$searchVal!='') {
			$condition[] = "(User.name LIKE (\"%" .
				$searchVal . "%\") OR Redemption.redeem_for LIKE (\"%" .
				$searchVal . "%\") OR Redemption.point LIKE (\"%" .
				$searchVal . "%\") OR Redemption.redemption_code LIKE (\"%" .
				$searchVal . "%\") OR  DATE_FORMAT(Redemption.recharge_date,\"%d %b, %Y\") LIKE (\"%" .
				$searchVal .
				"%\"))";
		}
		$data = $this->Redemption->find(
			'all',
			array(
				'fields'     => array(
					'User.name',
					'Redemption.*'
				),
				'conditions' => $condition,
				'order'      => $orderBY,
				'joins'      => array(
					array(
						'table'      => 'users',
						'alias'      => 'User',
						'type'       => 'INNER',
						'conditions' => array('Redemption.user_id=User.id')
					)
				)
			)
		);
		$content = '';
		
		// Generate column headers
		if (!empty($data)) {
			$content .= "User,Reward Type,Points Spent, Date and Time" . "\n";
			
			// Fill rows with data
			foreach ($data as $redeem) {
				
				if ($redeem['Redemption']['redeem_for'] == 1) {
					$redeemCode =  '-';
					$redeemFor = __('Recharge');
				} else if ($redeem['Redemption']['redeem_for'] == 2) {
					$redeemCode =  $redeem['Redemption']['redemption_code'];
					$redeemFor = __('Customer Support');
				} else if ($redeem['Redemption']['redeem_for'] == 3) {
					$redeemCode =  $redeem['Redemption']['redemption_code'];
					$redeemFor = __('Download');
				}
				$content .=
					$redeem['User']['name'] . "," .
					$redeemFor . "," .
					$redeem['Redemption']['point'] . "," .
					date('Y-m-d H:i:s', strtotime($redeem['Redemption']['recharge_date'])) .
					"\n";
			}
		}

		// Generate new file
		$path = realpath('../../app/webroot/uploads/') . '/';
		$FileName = 'RewardRedemptions.csv';
		$NewFile = $path . $FileName;
		file_put_contents($NewFile, $content);
		header('Content-Type: application/csv'); 
		header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
		readfile($NewFile);
		exit();	
	}

	/**
	 * Calculate gross sales amount (with taxes) for users
	 */
	function total_user_sales($fromDate = '', $toDate = '', $operator = null) {
		$this->autoRender = false;

		// Set conditions
		$condition = array(
			'Recharge.status'         => 1,
			'Recharge.payment_method' => array(1, 2),
			'Recharge.user_type'      => 1);

		// Get to and from date
		$fromDateArr = explode('-', $this->params['named']['from']);
		$fromDate = @$this->params['named']['from'];
		$toDateArr = explode('-', $this->params['named']['to']);
		$toDate = @$this->params['named']['to'];

		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'        => 1,
					'Recharge.payment_method' => array(1, 2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
				);
		}

		// Get mobile operator
		$operator = $this->params['named']['operator'];
		
		// If a mobile operator is specified, add it to conditions array
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}

		// If a payment method is specified, add it to conditions array
		if (@$this->params['named']['payment_method'] != '') {
			$condition['Recharge.payment_method'] = $this->params['named']['payment_method'];
		}

		// Get data from recharges table
		$saleData = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array('sum(total_amount) As sale'),
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
		
		// If results were obtained, return them
		if ($saleData[0][0]['sale']) {
			return $saleData[0][0]['sale'];

		// Otherwise, return 0
		} else {
			return 0;
		}
	}
	
	/**
	 * Calculate net sales amount (without taxes) for users
	 */
	function net_user_sales($fromDate = '', $toDate = '', $operator = null) {
		$this->autoRender = false;

		// Set conditions
		$condition = array(
			'Recharge.status'         => 1,
			'Recharge.payment_method' => array(1, 2),
			'Recharge.user_type'      => 1);

		// Get to and from date
		$fromDateArr = explode('-', $this->params['named']['from']);
		$fromDate = @$this->params['named']['from'];
		$toDateArr = explode('-', $this->params['named']['to']);
		$toDate = @$this->params['named']['to'];
		
		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'        => 1,
					'Recharge.payment_method' => array(1, 2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
				);
		}

		// Get mobile operator
		$operator = $this->params['named']['operator'];
		
		// If a mobile operator is specified, add it to conditions array
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}
		
		// If a payment method is specified, add it to conditions array
		if (@$this->params['named']['payment_method'] != '') {
			$condition['Recharge.payment_method'] = $this->params['named']['payment_method'];
		}

		// Get data from recharges table
		$saleData = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array('sum(amount) As sale'),
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
		
		// If results were obtained, return them
		if ($saleData[0][0]['sale']) {
			return $saleData[0][0]['sale'];

		// Otherwise, return 0
		} else {
			return 0;
		}
	}
	
	/**
	 * Calculate number of user recharges
	 */
	function total_user_recharges($fromDate = '', $toDate = '', $operator = null) {
		$this->autoRender = false;

		// Set conditions		
		$condition = array(
			'Recharge.status'        => 1,
			'Recharge.payment_method' => array(1, 2),
			'Recharge.user_type'     => 1
		);

		// Get to and from date
		$fromDateArr = explode('-', $this->params['named']['from']);
		$fromDate = @$this->params['named']['from'];
		$toDateArr = explode('-',$this->params['named']['to']);
		$toDate = @$this->params['named']['to'];

		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'        => 1,
					'Recharge.payment_method' => array(1, 2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
				);
		}

		// Get mobile operator
		$operator = $this->params['named']['operator'];
		
		// If a mobile operator is specified, add it to conditions array
		if ($operator!='') {
			$condition['Operator.id'] = $operator;
		}
		
		// If a payment method is specified, add it to conditions array
		if (@$this->params['named']['payment_method'] != '') {
			$condition['Recharge.payment_method'] = $this->params['named']['payment_method'];
		}

		// Get data from recharges table
		$saleData = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array('count(Recharge.id) As total'),
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

		// If results were obtained, return them
		if ($saleData[0][0]['total']) {
			return $saleData[0][0]['total'];
		}

		// Otherwise, return 0
		else {
			return 0;
		}
	}

	/**
	 * Calculate total points awarded
	 */
	function total_points_awarded($fromDate = '', $toDate = '') {
		$this->autoRender = false;

		// Set conditions
		$condition = array(
			'Recharge.status'        => 1,
			'Recharge.payment_method' => array(1, 2),
			'Recharge.user_type'     => 1);

		// Get to and from date
		$fromDateArr = explode('-', $this->params['named']['from']);
		$fromDate = @$this->params['named']['from'];
		$toDateArr = explode('-', $this->params['named']['to']);
		$toDate = @$this->params['named']['to'];
		
		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'        => 1,
					'Recharge.payment_method' => array(1, 2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
				);
		}

		// Get mobile operator
		$operator = $this->params['named']['operator'];
		
		// If a mobile operator is specified, add it to conditions array
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}
		
		// If a payment method is specified, add it to conditions array
		if (@$this->params['named']['payment_method'] != '') {
			$condition['Recharge.payment_method'] = $this->params['named']['payment_method'];
		}

		// Get data from recharges table
		$saleData = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array('sum(Recharge.points) As point'),
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

		// If results were obtained, return them
		return $saleData[0][0]['point'];
	}

	/**
	 * Calculate reseller sales total
	 */
	function total_reseller_sales($fromDate = '', $toDate = '', $operator = null) {
		$this->autoRender = false;

		// Set conditions
		$condition = array(
			'Recharge.status'        => 1,
			'Recharge.payment_method' => array(1, 2),
			'Recharge.user_type'     => 2
		);

		// Get to and from date
		$fromDateArr = explode('-', $this->params['named']['from']);
		$fromDate = @$this->params['named']['from'];
		$toDateArr = explode('-', $this->params['named']['to']);
		$toDate = @$this->params['named']['to'];

		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'        => 1,
					'Recharge.payment_method' => array(1,2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
				);
		}

		// Get mobile operator
		$operator = $this->params['named']['operator'];

		// Get sponsor
		$sponsor = $this->params['named']['sponsor'];

		// Get reseller
		$reseller = $this->params['named']['reseller'];

		// If a mobile operator is specified, add it to conditions array
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}

		// If a payment method is specified, add it to conditions array
		if (@$this->params['named']['payment_method'] != '') {
			$condition['Recharge.payment_method'] = $this->params['named']['payment_method'];
		}

		// If a sponsor is specified, add it to conditions array
		if ($sponsor != '') {
			$condition['Sponsor.id'] = $sponsor;
		}

		// If a reseller is specified, add it to conditions array
		if ($reseller != '') {
			$condition['User.id'] = $reseller;
		}

		// Get data from recharges table
		$saleData = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array('sum(total_amount) As sale'),
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
					),
					array(
						'table'      => 'sponsors',
						'alias'      => 'Sponsor',
						'type'       => 'INNER',
						'conditions' => array('User.sponsor_id=Sponsor.id')
					)
				)
			)
		);

		// If results were obtained, return them
		if ($saleData[0][0]['sale']) {
			return $saleData[0][0]['sale'];
		}

		// Otherwise, return 0
		else {
			return 0;
		}
	}
	
	/**
	 * Calculate total number of reseller recharges
	 */
	function total_reseller_recharges($fromDate = '', $toDate = '', $operator = null) {
		$this->autoRender = false;

		// Set conditions
		$condition = array(
			'Recharge.status'        => 1,
			'Recharge.payment_method' => array(1, 2),
			'Recharge.user_type'     => 2
		);

		// Get to and from date
		$fromDateArr = explode('-', $this->params['named']['from']);
		$fromDate = @$this->params['named']['from'];
		$toDateArr = explode('-', $this->params['named']['to']);
		$toDate = @$this->params['named']['to'];
		
		// If to and from dates are specified, add them to conditions array
		if ($fromDate != '' &&
			$toDate != '' &&
			checkdate($fromDateArr[1], $fromDateArr[2], $fromDateArr[0]) &&
			checkdate($toDateArr[1], $toDateArr[2], $toDateArr[0])) {
				$condition = array(
					'Recharge.status'        => 1,
					'Recharge.payment_method' => array(1,2),
					"DATE(recharge_date) BETWEEN \"" . $fromDate . "\" AND \"" . $toDate . "\""
				);
		}

		// Get mobile operator
		$operator = $this->params['named']['operator'];

		// Get sponsor			
		$sponsor = $this->params['named']['sponsor'];

		// Get reseller
		$reseller =$this->params['named']['reseller'];
		
		// If a mobile operator is specified, add it to conditions array
		if ($operator != '') {
			$condition['Operator.id'] = $operator;
		}

		// If a payment method is specified, add it to conditions array
		if (@$this->params['named']['payment_method'] != '') {
			$condition['Recharge.payment_method'] = $this->params['named']['payment_method'];
		}

		// If a sponsor is specified, add it to conditions array
		if ($sponsor != '') {
			$condition['Sponsor.id'] = $sponsor;
		}

		// If a reseller is specified, add it to conditions array
		if ($reseller != '') {
			$condition['User.id'] = $reseller;
		}

		// Get data from recharges table
		$saleData = $this->Recharge->find(
			'all',
			array(
				'conditions' => $condition,
				'fields'     => array('count(Recharge.id) As total'),
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
					),
					array(
						'table'      => 'sponsors',
						'alias'      => 'Sponsor',
						'type'       => 'INNER',
						'conditions' => array('User.sponsor_id=Sponsor.id')
					)
				)
			)
		);
		
		// If results were obtained, return them
		if ($saleData[0][0]['total']) {
			return $saleData[0][0]['total'];

		// Otherwise, return 0
		} else {
			return 0;
		}
	}
}
