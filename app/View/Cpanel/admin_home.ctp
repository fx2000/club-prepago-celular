<?php
/**
 * Dashboard
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Cpanel
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
echo $this->Html->css('charisma-app');
$users = $this->requestAction('user/total_users');
$sponsors = $this->requestAction('sponsor/total_sponsors');
$resellers = $this->requestAction('reseller/total_resellers');
$staff = $this->requestAction('staff/total_staff');
?>
<div>
	<ul class="breadcrumb">
		<li>
			<?php
				echo $this->Html->link(
					__('Home'),
					array(
						'controller' => 'cpanel',
						'action'     => 'admin_index'
					)
				);
			?>
		</li>
	</ul>
</div>

<!-- Home view for administrator profile-->
<?php 
	if ($this->Session->read('admin_type') == 3) {
?>
<div class="row-fluid" style="margin:30px 0 10px 0;">
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-user"></span><div>Users</div>'),
			array(
				'controller' => 'user',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $users . __(' Users'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-user"></span><div>Sponsors</div>'),
			array(
				'controller' => 'sponsor',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $sponsors . __(' Sponsors'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-user"></span><div>Resellers</div>'),
			array(
				'controller' => 'reseller',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $resellers . __(' Resellers'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-users"></span><div>Staff Members</div>'),
			array(
				'controller' => 'staff',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $staff . __(' Staff Members'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
</div>
<div class="row-fluid" style="margin:20px 0;">
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-star-off"></span><div>Inventory</div>'),
			array(
				'controller' => 'inventory',
				'action' => 'index'
			),
			array(
				'data-rel' => 'tooltip',
				'title'    => __('View inventory'),
				'class'    => 'well span3 top-block',
				'escape'   => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-info"></span><div>Check Recharge</div>'),
			array(
				'controller' => 'recharge',
				'action'     => 'status'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => __('Check the status of a recharge'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-book"></span><div>Payment Notifications</div>'),
			array(
				'controller' => 'payments',
				'action'     => 'payment_notifications'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => __('View payment notifications'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-cart"></span><div>Transactions</div>'),
			array(
				'controller' => 'report',
				'action'     => 'transactions'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => __('View Transactions report'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
</div>

<!-- Home view for Supervisor profile-->
<?php 
	} else if ($this->Session->read('admin_type') == 2) {
?>
<div class="row-fluid" style="margin:30px 0 10px 0;">
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-user"></span><div>Users</div>'),
			array(
				'controller' => 'user',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $users . __(' Users'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-user"></span><div>Sponsors</div>'),
			array(
				'controller' => 'sponsor',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $sponsors . __(' Sponsors'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-user"></span><div>Resellers</div>'),
			array(
				'controller' => 'reseller',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $resellers . __(' Resellers'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-book"></span><div>Transactions</div>'),
			array(
				'controller' => 'report',
				'action'     => 'transactions'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => __('View transactions report'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
</div>
<div class="row-fluid" style="margin:20px 0;">
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-star-off"></span><div>Inventory</div>'),
			array(
				'controller' => 'inventory',
				'action' => 'index'
			),
			array(
				'data-rel' => 'tooltip',
				'title'    => __('View inventory'),
				'class'    => 'well span3 top-block',
				'escape'   => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-info"></span><div>Check Recharge</div>'),
			array(
				'controller' => 'recharge',
				'action'     => 'status'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => __('Check the status of a recharge'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-book"></span><div>Payment Notifications</div>'),
			array(
				'controller' => 'payments',
				'action'     => 'payment_notifications'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => __('View payment notifications'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
</div>

<!-- Home view for Customer Support profile-->
<?php 
	} else if ($this->Session->read('admin_type') == 1) {
?>
<div class="row-fluid" style="margin:30px 0 10px 0;">
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-user"></span><div>Users</div>'),
			array(
				'controller' => 'user',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $users . __(' Users'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-user"></span><div>Resellers</div>'),
			array(
				'controller' => 'reseller',
				'action'     => 'index'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => $resellers . __(' Resellers'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
	<?php
		echo $this->Html->link(
			__('<span class="icon32 icon-blue icon-info"></span><div>Check Recharge</div>'),
			array(
				'controller' => 'recharge',
				'action'     => 'status'
			),
			array(
				'data-rel'   => 'tooltip',
				'title'      => __('Check the status of a recharge'),
				'class'      => 'well span3 top-block',
				'escape'     => false
			)
		);
	?>
</div>
<?php
	}
?>
