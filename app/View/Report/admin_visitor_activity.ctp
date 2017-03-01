<?php
/**
 * Visitor activity report view
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Report
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
$totalUser = $this->requestAction('user/TotalUsers?input_date=' . @$_REQUEST['input_date']);
?>
<div>
	<ul class="breadcrumb">
		<li>
			<?php
				echo $this->Html->link(
					'Home',
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
					'Activity',
					array(
						'controller' => 'inventory',
						'action'     => 'visitorActivity'
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
			<h2><i class="icon-list-alt"></i> Activity</h2>
		</div>
		<div class="box-content " style="margin-bottom:20px;">
			<div class="pull-right"><form action="" class="form-horizontal" method="get" id="RechargeAdminRedemptionForm" accept-charset="utf-8">
				<input type="date" class="input-small datepicker" id="input_date" name="input_date"
					placeholder="Input Date" data-rel='tooltip' data-original-title='Input date'
					value="<?php echo @$_REQUEST['input_date']; ?>">&nbsp;
				<?php
					echo $this->Form->Submit(
						'Submit',
						array(
							'class' => 'btn btn-primary pull-right',
							'div'   => false
						)
					);
				?>&nbsp;&nbsp;
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="box-content">
			<span class="blue" style="font-size: 16px;font-weight: bold;">
				Number of new users registered : <?php echo ($totalUser != null) ? $totalUser : '0'; ?>
			</span>
			<br/><br/>
			<span class="blue" style="font-size: 16px;font-weight: bold;">
				Most popular mobile operator : <?php echo (@$data[0]['Operator']['name'] != '') ? $data[0]['Operator']['name'] : 'N/A'; ?>
			</span>
			<br/>
		</div>
	</div>
</div>
