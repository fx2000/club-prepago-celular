<?php
/**
 * Inventory view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Inventory
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
$resellerBalance = $this->requestAction('reseller/total_reseller_balance');
$userBalance = $this->requestAction('user/total_user_balance');
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
	<?php
		$i = 0;

		foreach ($userdata as $data) {

			if ($i == 0 || $i % 3 == 0) {
	?>
	<div class="row-fluid">
		<?php
			}
		?>
		<div class="box span4">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i><?php echo $data['Operator']['name'] ?></h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
				<h1><center>Bs. <?php echo $data['Operator']['balance'] ?></center></h1>
			</div>
		</div>
	<?php
			if ($i == 2 || ($i - 2) % 3 == 0) {
			}
			$i++;
		}
	?>
	</div>
	<div class="row-fluid">
		<div class="box span4">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i><?php echo __('User Prepaid Balance'); ?></h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
				<h1><center>Bs. <?php echo round($userBalance[0][0]['total'], 2); ?></center></h1>
			</div>
		</div>
		<div class="box span4">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i><?php echo __('Reseller Prepaid Balance'); ?></h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
				<h1><center>Bs. <?php echo round($resellerBalance[0][0]['total'], 2); ?></center></h1>
			</div>
		</div>
	</div>
	<?php
		if ($Admindata['Admin']['type'] == 3) {
	?>
		<div class="row-fluid">
			<div class="box span12">
				<div class="box-header well" data-original-title>
					<h2><i class="icon-th"></i><?php echo __(' Enter new purchase into inventory'); ?></h2>
				</div>
				<div class="box-content"  style="font-size:15px;">
					<?php
						echo $this->Form->create(
							'Inventory',
							array(
								'url'   => array(
									'controller' => 'inventory',
									'action'     => 'index'
								),
								'class' => 'form-horizontal',
								'name'  => 'add_prepaid'
							)
						);
					?>
					<div class="control-group">
						<label class="control-label"><?php echo __('Mobile Operator'); ?></label>
						<div class="controls">
							<select name="data[Inventory][operator]" id="operator" class="input-medium" data-rel=tooltip data-original-title='Operator'>
								<option value=""><?php echo __('Select...'); ?></option>
								<?php
									foreach ($Operatordata as $key => $operator) {
										echo "<option value='" . $key . "'>" . $operator . "</option>";
									}
								?>
							</select>
							<script language="javascript" type="text/javascript">
								var f1 = new LiveValidation('operator');
								f1.add( Validate.Presence);
							</script>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo __('Amount'); ?></label>
						<div class="controls">
							<input type="text" class="input-medium" id="amount" name="data[Inventory][amount]"
								data-rel='tooltip' data-original-title='Amount' placeholder=<?php echo __('Amount'); ?>>
								<script language="javascript" type="text/javascript">
									var f1 = new LiveValidation('amount');
									f1.add( Validate.Presence);
									f1.add( Validate.NumberValidFloat);
								</script>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo __('Document Number'); ?></label>
						<div class="controls">
							<input type="text" class="input-medium" id="document_number" name="data[Inventory][document_number]"
								data-rel='tooltip' data-original-title='Document Number' placeholder=<?php echo __('Document Number'); ?> maxlength="255" >&nbsp;
								<script language="javascript" type="text/javascript">
									var f1 = new LiveValidation('document_number');
									f1.add( Validate.Presence);
								</script>
						</div>
					</div>
					<div class="form-actions">
						<?php
							echo $this->Form->Button(
								'Submit',
								array(
									'class'=>'btn btn-primary',
									'div'=>false
								)
							);
						?>&nbsp;&nbsp;
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div><!--/span-->
		</div>
		<?php
			if (!empty($AccHistory)) {
		?>
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th"></i><?php echo __(' Airtime Purchase History'); ?></h2>
					</div>
					<div class="box-content" style="font-size:15px;">
						<table class="table table-striped table-bordered bootstrap-datatable Inventorydatatable">
							<thead>
								<tr>
									<th><?php echo __('Mobile Operator'); ?></th>
									<th><?php echo __('Amount'); ?></th>
									<th><?php echo __('Document Number'); ?></th>
									<th class="hidden-phone "><?php echo __('Date & Time'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($AccHistory as $val) {
								?>
									<tr>
										<td><?php echo $val['Operator']['name']; ?></td>
										<td><?php echo $val['AirtimePurchaseHistory']['amount']; ?></td>
										<td><?php echo $val['AirtimePurchaseHistory']['document_number']; ?></td>
										<td  class="hidden-phone">
											<?php
												echo ($val['AirtimePurchaseHistory']['purchase_date'] != '0000-00-00 00:00:00')
													? date('Y-m-d H:i:s', strtotime($val['AirtimePurchaseHistory']['purchase_date'])) : 'N/A';
											?>
										</td>
									</tr>
								<?php
									}
								?>
							</tbody>
						<table>
					</div>
				</div>
			</div>
		<?php
			}
		?>
	<?php
		}
	?>
</div>
