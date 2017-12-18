<?php
/**
 * Main coupons view
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Coupon
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<div>
	<ul class="breadcrumb">
		<li>
			<?php
				echo $this->Html->link(
					__('Home'),
					array(
						'controller' => 'cpanel',
						'action'     => 'home'
					)
				);
			?>
		</li>
	</ul>
</div>
<?php
	if ($this->Session->read('alert') != '') {
?>
<div class="alert <?php echo ($this->Session->read('success') == 1) ? 'alert-success' : 'alert-error' ?>">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<strong>
		<?php
			echo $this->Session->read('alert');
			$_SESSION['alert'] = '';
		?>
	</strong>
</div>
<?php
	}
?>
<div class="row-fluid ">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i><?php echo __(' Coupons'); ?></h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Coupondatatable">
				<thead>
					<tr>
						<th class="hidden-phone "><?php echo __('Image'); ?></th>
						<th class="hidden-phone "><?php echo __('Description'); ?></th>
						<th><?php echo __('Amount'); ?></th>
						<th class="hidden-phone "><?php echo __('Cant'); ?></th>
						<th class="hidden-phone "><?php echo __('Points'); ?></th>
						<th class="hidden-phone "><?php echo __('Status'); ?></th>
						<th class="hidden-phone "><?php echo __('Due Date'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<?php
					if (empty($coupondata)) {
				?>
				 <tbody>
					<tr>
						<td colspan="5"><?php echo __('No coupons available'); ?></td>
					</tr>
				</tbody>
				<?php
					} else {
				?>
				<tbody>
					<?php
						foreach($coupondata as $val){
					?>
					<tr>
						<td class="hidden-phone">
							<?php
								echo $this->Html->image('coupons/' . $val['Coupon']['image'], array('width' => '150'));
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['Coupon']['description']; ?></td>
						<td >$<?php echo $val['Coupon']['amount'];?></td>
						<td class="hidden-phone"><?php echo $val['Coupon']['cant']; ?></td>
						<td class="hidden-phone"><?php echo $val['Coupon']['points']; ?></td>
						<td class="hidden-phone">
							<?php
								if ($val['Coupon']['status'] == 1) {
									echo __("<span class='label label-success'>Active</span>");
								} else {
									echo __("<span class='label label-warning'>Inactive</span>");
								}
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['Coupon']['due_date']; ?></td>
						<td class="center">
							<?php
								echo $this->html->link(
									__('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span>'),
									array(
										'controller' => 'coupon',
										'action'     => 'edit',
										base64_encode($val['Coupon']['id'])
									),
									array(
										'class'      => 'btn btn-small',
										'escape'     => false
									)
								);
								echo $this->html->link(
									__('<i class="icon-trash icon-black"></i></i><span class="hidden-phone">Delete</span>'),
									array(
										'controller' => 'coupon',
										'action'     => 'delete',
										base64_encode($val['Coupon']['id'])
									),
									array(
										'class'      => 'btn btn-small del_rec',
										'escape'     => false
									)
								);
							?>
						</td>
					</tr>
				</tbody>
				<?php
						}
					}
				?>
			</table>
		</div>
	</div>
</div>
