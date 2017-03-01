<?php
/**
 * Database Configuration
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
require_once "vendor/autoload.php";

class Dbconn {

	// Class Variables
	var $_dbServer;
	var $_uid;
	var $_pass;
	var $_dbName;
	var $_conn;

	/*
	 * This connects to the local database
	 */
	function Dbconn() {

		// Club Prepago mysql credentials
		$this->_dbServer = 'localhost';
		$this->_uid = 'clubprepago';
		$this->_pass = 'fs7mHr6AYR6yWN3s';
		$this->_dbName = 'club_prepago';
		$this->_conn = @($GLOBALS["___mysqli_ston"] = mysqli_connect($this->_dbServer,  $this->_uid,  $this->_pass));
		((bool)mysqli_set_charset( $this->_conn, "UTF8"));

		// If connection fails
		if ($this->_conn === FALSE) {
			$this->msgBox("Unable To Connect to Database: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			return FALSE;
		}


		// If selection fails
		if (((bool)mysqli_query( $this->_conn, "USE " . $this->_dbName)) === FALSE) {
			$this->msgBox("Unable To Select Database: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			return FALSE;
		}
		return TRUE;
	}

	/*
	 * Generate error messages
	 */
	function msgBox($msg) {
		echo "<script>window.onload=function showError() {alert(\"" . $msg . "\");}</script>";
	}

	/*
	 * Generate mysql queries
	 */
	function fireQuery($qry) {

		if ($qry == "" || $this->_conn === FALSE) {
			return;
		}
		$result = @mysqli_query($GLOBALS["___mysqli_ston"], $qry); 

		if ($result === FALSE) {
			$this->msgBox("Unable To Fire Query: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			return FALSE;
		}
		return $result;
	}

	/*
	 * Count mysql rows
	 */
	function rowCount($result) {

		if ($result == FALSE || $this->_conn === FALSE) {
			return;
		}
		else {
			return @mysqli_num_rows($result);
		}
	}

	/*
	 * Get a mysql row
	 */
	function fetchRow($result) {

		if ($result === FALSE) {
			return;
		}
		$row = @mysqli_fetch_row($result);

		if ($row === FALSE) {
			return FALSE;
		}
		return $row;
	}

	/*
	 * Get a mysql value
	 */
	function fetchAssoc($result) {

		if ($result === FALSE) {
			return;
		}
		$row = @mysqli_fetch_assoc($result);

		if ($row === FALSE) {
			return FALSE;
		}
		return $row;
	}
}

