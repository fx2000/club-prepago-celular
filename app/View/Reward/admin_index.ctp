<?php
/**
 * Main rewards view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Reward
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
			<h2><i class="icon-user"></i><?php echo __(' Rewards'); ?></h2>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable Rewarddatatable">
				<thead>
					<tr>
						<th><?php echo __('Points'); ?></th>
						<th><?php echo __('Recharge Amount'); ?></th>
						<th class="hidden-phone "><?php echo __('Image'); ?></th>
						<th class="hidden-phone "><?php echo __('Description'); ?></th>
						<th class="hidden-phone "><?php echo __('Status'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<?php
					if (empty($rechargedata)) {
				?>
				 <tbody>
					<tr>
						<td colspan="5"><?php echo __('No rewards available'); ?></td>
					</tr>
				</tbody>
				<?php
					} else {
				?>
				<tbody>
					<?php
						foreach($rechargedata as $val){
					?>
					<tr>
						<td><?php echo $val['Reward']['points'] . __(' points');?></td>
						<td >Bs. <?php echo $val['Reward']['value'];?></td>
						<td class="hidden-phone">
							<?php
								echo $this->Html->image('rewards/' . $val['Reward']['image'], array('width' => '150'));
							?>
						</td>
						<td class="hidden-phone"><?php echo $val['Reward']['description']; ?></td>
						<td class="hidden-phone">
							<?php
								if ($val['Reward']['status'] == 1) {
									echo __("<span class='label label-success'>Active</span>");
								} else {
									echo __("<span class='label label-warning'>Inactive</span>");
								}
							?>
						</td>
						<td class="center">
							<?php
								echo $this->html->link(
									__('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span>'),
									array(
										'controller' => 'reward',
										'action'     => 'edit',
										base64_encode($val['Reward']['id'])
									),
									array(
										'class'      => 'btn btn-small',
										'escape'     => false
									)
								);
								echo $this->html->link(
									__('<i class="icon-trash icon-black"></i></i><span class="hidden-phone">Delete</span>'),
									array(
										'controller' => 'reward',
										'action'     => 'delete',
										base64_encode($val['Reward']['id'])
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
