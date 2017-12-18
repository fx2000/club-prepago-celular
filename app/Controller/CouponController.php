<?php
/**
 * Coupon Controller
 *
 * This file handles coupons in general. Creation, edition, deletion,
 * mahages of coupons
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.Controller
 * @since         Club Prepago Celular(tm) v 1.0.0
 */

 class CouponController extends AppController {

 	var $uses = array('Coupon');

 	var $components = array(
 		'Validation',
 		'ImageUpload'
 	);

 	/**
 	 * List Coupons
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

 		// Get coupons from coupons table
 		$data = $this->Coupon->find(
 			'all',
 			array(
 				'conditions' => array(
 					'delete_status' => 0,
 				),
 				'order'      => 'id asc'
 			)
 		);
 		$this->set('coupondata', $data);
 	}

  /**
	 * Add new coupon
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

			// If all goes well, upload coupon image and save coupon
			if (@$this->request->data['Coupon']['img']['error'] == 0 &&
				$this->ImageUpload->img_size(@$this->request->data['Coupon']['img']['size']) == 1) {

					// Set image destination directory
					$destination_med = realpath('../../app/webroot/img/coupons/') . '/';

					// Set file details
					$FILE = $this->request->data['Coupon']['img'];
					$ext  = $this->ImageUpload->GetExt($FILE['name']);
					$imgname = strtotime(date('Y-m-d h:i:s'));
					$imgname = sha1($imgname) . '.' . $ext;
					$this->request->data['Coupon']['image'] = $imgname;

					// Upload image
					$this->ImageUpload->myupload($FILE, $destination_med, $imgname, NULL, NULL, $imgname);
					$this->Coupon->save($this->request->data);

					// Generate success message
					$this->Session->write('success', "1");
					$this->Session->write('alert', __('Coupon added successfully'));

					// Redirect back to rewards index
					$this->redirect('index');

			// If there was an error uploading the image, generate error message
    } else if (@$this->request->data['Coupon']['img']['error'] != 0 ) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Error in uploaded image'));
				$this->render();

			// If the image is too big, generate error message
    } else if ($this->ImageUpload->img_size(@$this->request->data['Coupon']['img']['size']) != 1) {
				$this->Session->write('success', "0");
				$this->Session->write('alert', __('Image Size should be less than 10MB'));
				$this->render();
			}
		}

    // Create Store model
		$this->loadModel('Store');

    // Get stores
 		$data = $this->Store->find(
 			'all',
 			array(
 				'conditions' => array(
 					'delete_status' => 0,
 				),
 				'order'      => 'id desc'
 			)
 		);
 		$this->set('storedata', $data);
	}

  /**
   * Edit coupon
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
      if (@$this->request->data['Coupon']['img']['error'] == 0 &&
        $this->ImageUpload->img_size(@$this->request->data['Coupon']['img']['size']) == 1) {

          // Set image destination directory
          $destination_med = realpath('../../app/webroot/img/coupons/') . '/';
          $FILE = $this->request->data['Coupon']['img'];
          $ext  = $this->ImageUpload->GetExt($FILE['name']);

          // Set file details
          $imgname = strtotime(date('Y-m-d h:i:s'));
          $imgname = sha1($imgname) . '.' . $ext;
          $this->request->data['Coupon']['image'] = $imgname;

          // Upload image
          $this->ImageUpload->myupload($FILE, $destination_med, $imgname, NULL, NULL, $imgname);

      // If it fails, generate error message
    } else if (@$this->request->data['Coupon']['img']['error'] == 0 &&
        $this->ImageUpload->img_size(@$this->request->data['Coupon']['img']['size']) != 1) {
          $this->Session->write('success', "0");
          $this->Session->write('alert', 'Image Size should be less than 10MB');
          $this->redirect('edit/' . base64_encode($this->request->data['Coupon']['id']));
      }

      // Save and generate success message
      $this->Coupon->save($this->request->data);
      $this->Session->write('success', "1");
      $this->Session->write('alert', 'Coupon updated successfully');
      $this->redirect('index');
    }  else {

      // Find Coupon
      if (is_numeric(base64_decode($id))) {
        $this->request->data = $this->Coupon->find(
          'first',
          array(
            'conditions' => array(
              'id' => base64_decode($id)
            )
          )
        );

      // If no coupon is found or an invalid coupon is specified
      } else {
        $this->Session->write('success', "0");
        $this->Session->write('alert', "Invalid Coupon");
      }
    }
  }

  /**
	 * Delete a coupon
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
			$data['Coupon']['id'] = base64_decode($id);
			$data['Coupon']['delete_status'] = '1';

			// Generate a success message
			if ($this->Coupon->save($data)) {
				$this->Session->write('success', "1");
				$this->Session->write('alert', "Coupon deleted successfully.");

				// Redirect back to coupons index
				$this->redirect(
					array(
						'controller' => 'coupon',
						'action'     => 'index'
					)
				);
			}
		}
	}
}
