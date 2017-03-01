<?php
/**
 * Reward Controller
 *
 * This file handles the rewards system
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
class RewardController extends AppController {

	var $uses = array('Reward');

	var $components = array(
		'Validation',
		'ImageUpload'
	);

	/**
	 * List Rewards
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

		// Get rewards from rewards table
		$data = $this->Reward->find(
			'all',
			array(
				'conditions' => array(
					'delete_status' => 0,
				),
				'order'      => 'id desc'
			)
		);
		$this->set('rechargedata', $data);
	}

	/**
	 * Add new reward
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

		if (!empty($this->request->data)) {

			// If all goes well, upload reward image and save reward
			if (@$this->request->data['Reward']['img']['error'] == 0 &&
				$this->ImageUpload->img_size(@$this->request->data['Reward']['img']['size']) == 1) {

					// Set image destination directory
					$destination_med = realpath('../../app/webroot/img/rewards/') . '/';

					// Set file details
					$FILE = $this->request->data['Reward']['img'];
					$ext  = $this->ImageUpload->GetExt($FILE['name']);
					$imgname = strtotime(date('Y-m-d h:i:s'));
					$imgname = sha1($imgname) . '.' . $ext;
					$this->request->data['Reward']['image'] = $imgname;

					// Upload image
					$this->ImageUpload->myupload($FILE, $destination_med, $imgname, NULL, NULL, $imgname);
					$this->Reward->save($this->request->data);

					// Generate success message
					$this->Session->write('success', "1");
					$this->Session->write('alert', __('Reward added successfully'));

					// Redirect back to rewards index
					$this->redirect('index');

			// If there was an error uploading the image, generate error message
			} else if (@$this->request->data['Reward']['img']['error'] != 0 ) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Error in uploaded image'));
				$this->render();

			// If the image is too big, generate error message
			} else if ($this->ImageUpload->img_size(@$this->request->data['Reward']['img']['size']) != 1) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Image Size should be less than 10MB'));
				$this->render();
			}
		}
	}

	/**
	 * Edit reward
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

		if (!empty($this->request->data)) {

			// Check and upload image
			if (@$this->request->data['Reward']['img']['error'] == 0 &&
				$this->ImageUpload->img_size(@$this->request->data['Reward']['img']['size']) == 1) {

					// Set image destination directory
					$destination_med = realpath('../../app/webroot/img/rewards/') . '/';
					$FILE = $this->request->data['Reward']['img'];
					$ext  = $this->ImageUpload->GetExt($FILE['name']);

					// Set file details
					$imgname = strtotime(date('Y-m-d h:i:s'));
					$imgname = sha1($imgname) . '.' . $ext;
					$this->request->data['Reward']['image'] = $imgname;

					// Upload image
					$this->ImageUpload->myupload($FILE, $destination_med, $imgname, NULL, NULL, $imgname);

			// If it fails, generate error message
			} else if (@$this->request->data['Reward']['img']['error'] == 0 &&
				$this->ImageUpload->img_size(@$this->request->data['Reward']['img']['size']) != 1) {
					$this->Session->write('success', "0");
					$this->Session->write('alert', 'Image Size should be less than 10MB');
					$this->redirect('edit/' . base64_encode($this->request->data['Reward']['id']));
			}

			// Save and generate success message
			$this->Reward->save($this->request->data);
			$this->Session->write('success', "1");
			$this->Session->write('alert', 'Reward updated successfully');
			$this->redirect('index');
		}  else {

			// Find Reward
			if (is_numeric(base64_decode($id))) {
				$this->request->data = $this->Reward->find(
					'first',
					array(
						'conditions' => array(
							'id' => base64_decode($id)
						)
					)
				);

			// If no reward is found or an invalid reward is specified
			} else {
				$this->Session->write('success', "0");
				$this->Session->write('alert', "Invalid Reward");
			}
		}
	}

	/**
	 * Delete a reward
	 */
	public function admin_delete($id) {

		// Check if session is still valid
		$this->requestAction(
			array(
				'controller' => 'cpanel',
				'action'     => 'admin_checkSession'
			)
		);
		$this->autoRender = false;

		// Set delete status to 1
		if (is_numeric(base64_decode($id))) {
			$data['Reward']['id'] = base64_decode($id);
			$data['Reward']['delete_status'] = '1';

			// Generate a success message
			if ($this->Reward->save($data)) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', "Reward deleted successfully.");

				// Redirect back to rewards index
				$this->redirect(
					array(
						'controller' => 'reward',
						'action'     => 'index'
					)
				);
			}
		}
	}
}
