<?php
/**
 * Home Controller
 *
 * This file controls user account activation.
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class HomeController extends AppController {
	var $uses = array('User');

	/**
	 * User activation
	 */
	function activate() {

		// Set page title
		$this->pageTitle = __('Activation');

		$this->autoRender = false;
		$userId = $this->params['pass'][0];

		// Check user table for owner of activation code
		$user = $this->User->query(
			"SELECT *
				FROM users
				WHERE sha1(id) = " . "\"" . $userId . "\""
		);

		if (!empty($user)) {

			// If the user has already been verified
			if ($user[0]['users']['email_verify'] == 1 && $user[0]['users']['status'] == 1) {
				echo '<center><img src="' . Router::url('/img/', true) . 'logo.png" alt="Club Prepago Celular"></center>';
				echo '<center>
						<div style="font-family:Tahoma;">
							<h2>Tu usuario ya ha sido activado y verificado correctamente</h2>
							</br>
							Puedes entrar a la aplicación de Club Prepago Celular cuando quieras
							</br>
							Si tienes algún problema, escríbenos a <a href="mailto:soporte@clubprepago.com">soporte@clubprepago.com</a>
							</br>
							o llámanos al <b>+507 388-6220</b>
						</div>
					</center>'
				;

			// If the user is banned
			} else if ($user[0]['users']['banned'] == 1) {
				echo '<center><img src="' . Router::url('/img/', true) . 'logo.png" alt="Club Prepago Celular"></center>';
				echo '<center>
						<div style="font-family:Tahoma;">
							<h2>Este usuario ha sido baneado de Club Prepago</h2>
							</br>
							Si tienes alguna pregunta, escríbenos a <a href="mailto:soporte@clubprepago.com">soporte@clubprepago.com</a>
							</br>
							o llámanos al <b>+507 388-6220</b>
						</div>
					</center>'
				;

			// If the user is pending verification and activation
			} else {

				// Update users table
				$this->User->query(
					"UPDATE users
						SET email_verify = 1, status = 1
						WHERE sha1(id) = " . "\"" . $userId . "\""
				);
				echo '<center><img src="' . Router::url('/img/', true) . 'logo.png" alt="Club Prepago Celular"></center>';
				echo '<center>
						<div style="font-family:Tahoma;">
							<h2>Tu usuario ha sido verificado y activado correctamente</h2>
							</br>
							Puedes entrar a la aplicación de Club Prepago Celular cuando quieras
							</br>
							Si tienes algún problema, escríbenos a <a href="mailto:soporte@clubprepago.com">soporte@clubprepago.com</a>
							</br>
							o llámanos al <b>+507 388-6220</b>
						</div>
					</center>'
				;
			}

		// If the user Id is invalid
		} else {
			echo '<center><img src="' . Router::url('/img/', true) . 'logo.png" alt="Club Prepago Celular"></center>';
			echo '<center>
					<div style="font-family:Tahoma;">
						<div class="span12 center">
							<h2>ID de Activación Inválido</h2>
							</br>
							Si tienes algún problema, escríbenos a <a href="mailto:soporte@clubprepago.com">soporte@clubprepago.com</a>
							</br>
							o llámanos al <b>+507 388-6220</b>
						</div>
					</div>
				</center>'
			;
		}
	}
}
