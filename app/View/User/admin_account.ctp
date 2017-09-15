<?php
/**
 * User account view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.User
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
		<li>/</li>
		<li>
			<?php
				echo $this->Html->link(
					__('View Users'),
					array(
						'controller' => 'user',
						'action'     => 'index'
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
	<div class="row-fluid">
		<div class="box span6">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i><?php echo __(' Prepaid Balance'); ?></h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
				<h1><center>Bs. <?php echo $this->request->data['User']['balance']; ?></center></h1>
			</div>
		</div>
		<div class="box span6">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i><?php echo __(' Reward Points'); ?></h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
				<h1><center><?php echo $this->request->data['User']['points']; ?></center></h1>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="box span12">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i><?php echo __(' Adjust'); ?></h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
				<?php
					echo $this->Form->create(
						'',
						array(
							'class' => 'form-horizontal',
							'name'  => 'add_prepaid'
						)
					);
				?>
				<div class="control-group">
					<label class="control-label"><?php echo __(' Account Type'); ?></label>
					<div class="controls">
						<label class="radio">
							<input type="radio" name="data[User][name]" id="action1" value="1" checked >
							<?php echo __('Prepaid Balance'); ?>
						</label>
						<div style="clear:both"></div>
						<label class="radio">
							<input type="radio" name="data[User][name]" id="action2" value="2">
							<?php echo __('Reward Points'); ?>
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __(' Action'); ?></label>
					<div class="controls">
						<label class="radio">
							<input type="radio" name="data[User][action]" id="action1" value="1" checked >
							<?php echo __('Add'); ?>
						</label>
						<div style="clear:both"></div>
						<label class="radio">
							<input type="radio" name="data[User][action]" id="action2" value="2">
							<?php echo __('Subtract'); ?>
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __(' Amount'); ?></label>
					<div class="controls">
						<input type="text" class="input-small" id="amount" name="data[User][amount]" data-rel='tooltip'
							data-original-title='Amount' placeholder="Amount" >&nbsp;
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('amount');

							f1.add( Validate.Presence);
							f1.add( Validate.NumberValidFloat);
						</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo __(' Notes'); ?></label>
					<div class="controls">
						<input type="text" class="input-xxlarge" id="detail" name="data[User][detail]" data-rel='tooltip'
							data-original-title='Notes' placeholder="Notes" maxlength="255">&nbsp;
						<script language="javascript" type="text/javascript">
							var f1 = new LiveValidation('explaination');

							f1.add( Validate.Presence);
						</script>
					</div>
					<div class="form-actions">
						<?php
							echo $this->Form->Button(
								__('Submit'),
								array(
									'class' => 'btn btn-primary',
									'div'   => false
								)
							);
						?>&nbsp;&nbsp;
					</div>
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
	<?php
		if (!empty($AccHistory)) {
	?>
	<div class="row-fluid">
		<div class="box span12">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i><?php echo __('  Account History'); ?></h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
				<table class="table table-striped table-bordered bootstrap-datatable Restrxdatatable">
					<thead>
						<tr>
							<th class="hidden-phone"><?php echo __('Payment ID'); ?></th>
							<th class="hidden-phone"><?php echo __('Account Adjusted'); ?></th>
							<th><?php echo __('Amount'); ?></th>
							<th class="hidden-phone"><?php echo __('Notes'); ?></th>
							<th><?php echo __('Date & Time'); ?></th>
							<th><?php echo __('Authorized By'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($AccHistory as $val) {
						?>
						<tr>
							<td class="hidden-phone">
								<?php
									if ($val['AccountHistory']['payment_id'] != 0) {

										if (strlen($val['AccountHistory']['payment_id']) < 6) {
											$filler = 6 - strlen($val['AccountHistory']['payment_id']);

											for ($i = 0; $i < $filler; $i++) {
												echo '0';
											}
											echo $val['AccountHistory']['payment_id'];
										} else {
											echo $val['AccountHistory']['payment_id'];
										}
									} else {
										echo '-';
									}
								?>
							</td>
							<td class="hidden-phone"><?php echo ($val['AccountHistory']['account_type'] == 1) ? __('Prepaid Balance') : __('Reward Points'); ?></td>
							<td>
								<?php
									if ($val['AccountHistory']['account_type'] == 1) {
										if ($val['AccountHistory']['amount'] < 0) {
											?><p style="color:red"><?php echo 'Bs. ' . number_format((float)$val['AccountHistory']['amount'], 2, '.', '');?></p>
										<?php
										} else {
											echo 'Bs. ' . number_format((float)$val['AccountHistory']['amount'], 2, '.', '');
										}
									} else if ($val['AccountHistory']['account_type'] == 2) {
										if ($val['AccountHistory']['amount'] < 0) {
											?><p style="color:red"><?php echo number_format((float)$val['AccountHistory']['amount'], 2, '.', ''); ?></p>
										<?php
										} else {
											echo number_format((float)$val['AccountHistory']['amount'], 2, '.', '');
										}
									} else {
										echo number_format((float)$val['AccountHistory']['amount'], 2, '.', '');
									}

								?>
							</td>
							<td class="hidden-phone"><?php echo $val['AccountHistory']['detail']; ?></td>
							<td>
								<?php
									echo ($val['AccountHistory']['datetime'] != '0000-00-00 00:00:00') ?
										date('Y-m-d H:i:s', strtotime($val['AccountHistory']['datetime'])) :
										'N/A';
								?>
							</td>
							<td class="hidden-phone">
								<?php
									if ($val['AccountHistory']['staff_id'] != 0) {
										$staff = $this->requestAction(
											array(
												'controller' => 'User',
												'action'     => 'getStaffName',
												$val['AccountHistory']['staff_id']
											)
										);
										echo $staff['Admin']['name'];
									} else {
										echo '-';
									}
								?>
							</td>
						</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
		}
	?>
</div>
