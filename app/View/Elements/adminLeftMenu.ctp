<?php
/**
 * Sidebar
 *
 * This view generates the application's left sidebar
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Elements
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<!-- Sidebar begins -->
<div class="well nav-collapse sidebar-nav">
	<ul class="nav nav-tabs nav-stacked main-menu" style="margin-bottom:0px;">
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-home'></i><span class='hidden-tablet'> Home</span>"),
					array(
						'controller' => 'cpanel',
						'action'     => 'home'
					),
					array(
						'class'      => 'ajax-link',
						'id'         => 'slot10',
						'escape'     => false
					)
				);
			?>
		</li>

		<!-- Sidebar for administrator profile -->
		<?php
			if ($this->Session->read('admin_type') == 3) {
		?>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-user'></i><span class='hidden-tablet'> Users</span>"),
					'#',
					array(
						'id'      => 'slot0',
						'class'   => 'ajax-link',
						'escape'  => false
					)
				);
			?>
			<ol id="sub_menu0" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add User</span>"),
							array(
								'controller' => 'user',
								'action'     => 'add'
							),
							array(
								'id'         => 'add_user',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Users</span>"),
							array(
								'controller' => 'user',
								'action'     => 'index'
							),
							array(
								'id'         => 'view_user',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-user'></i><span class='hidden-tablet'> Sponsors</span>"),
					'#',
					array(
						'id'     => 'slot1',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu1" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add Sponsor</span>"),
							array(
								'controller' => 'sponsor',
								'action'     => 'add'
							),
							array(
								'id'         => 'add_sponsor',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Sponsors</span>"),
							array(
								'controller' => 'sponsor',
								'action'     => 'index'
							),
							array(
								'id'         => 'view_sponsor',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>	
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-user'></i><span class='hidden-tablet'> Resellers</span>"),
					'#',
					array(
						'id'     => 'slot2',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu2" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add Reseller</span>"),
							array(
								'controller' => 'reseller',
								'action'     => 'add'
							),
							array(
								'id'         => 'add_reseller',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Resellers</span>"),
							array(
								'controller' => 'reseller',
								'action'     => 'index'
							),
							array(
								'id'         => 'view_reseller',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-list-alt'></i><span class='hidden-tablet'> Reports</span>"),
					'#',
					array(
						'id'     => 'slot3',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu3" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Direct Sales</span>"),
							array(
								'controller' => 'report',
								'action'     => 'direct_sales'
							),
							array(
								'id'         => 'direct_sales',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'>  Reseller Sales</span>"),
							array(
								'controller' => 'report',
								'action'     => 'reseller_sales'
							),
							array(
								'id'         => 'reseller_sales',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Transactions</span>"),
							array(
								'controller' => 'report',
								'action'     => 'transactions'
							),
							array(
								'id'         => 'transactions',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> User Purchases</span>"),
							array(
								'controller' => 'report',
								'action'     => 'user_purchases'
							),
							array(
								'id'         => 'user_purchases',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Reseller Purchases</span>"),
							array(
								'controller' => 'report',
								'action'     => 'reseller_purchases'
							),
							array(
								'id'         => 'reseller_purchases',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-star-empty'></i><span class='hidden-tablet'> Inventory</span>"),
					'#',
					array(
						'id'     => 'slot4',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu4" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Inventory</span>"),
							array(
								'controller' => 'inventory',
								'action'     => 'index'
							),
							array(
								'id'         => 'inventory',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Inventory Warnings</span>"),
							array(
								'controller' => 'inventory',
								'action'     => 'warning'
							),
							array(
								'id'         => 'inventory_warning',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>	
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-star'></i><span class='hidden-tablet'> Rewards</span>"),
					'#',
					array(
						'id'     => 'slot5',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu5" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add Reward</span>"),
							array(
								'controller' => 'reward',
								'action'     => 'add'
							),
							array(
								'id'         => 'add_reward',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Rewards</span>"),
							array(
								'controller' => 'reward',
								'action'     => 'index'
							),
							array(
								'id'         => 'view_reward',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>	
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-info-sign'></i><span class='hidden-tablet'> Recharge Status</span>"),
					'#',
					array(
						'id'     => 'slot6',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu6" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Check Recharge</span>"),
							array(
								'controller' => 'recharge',
								'action'     => 'status'
							),
							array(
								'id'         =>'rechage_status',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Failed Recharges</span>"),
							array(
								'controller' => 'recharge',
								'action'     => 'failed'
							),
							array(
								'id'         => 'rechage_failed',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-cog'></i><span class='hidden-tablet'> Settings</span>"),
					'#',
					array(
						'id'     => 'slot7',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu7" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Taxes</span>"),
							array(
								'controller' => 'setting',
								'action'     => 'tax'
							),
							array(
								'id'         => 'edit_tax',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Reward Points</span>"),
							array(
								'controller' => 'setting',
								'action'     => 'edit_points'
							),
							array(
								'id'         => 'view_setting',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Fees and Discounts</span>"),
							array(
								'controller' => 'setting',
								'action'     => 'reseller'
							),
							array(
								'id'         => 'reseller_setting',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Mobile Operators</span>"),
							array(
								'controller' => 'setting',
								'action'     => 'operator'
							),
							array(
								'id'         => 'edit_operator',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> TrxEngine</span>"),
							array(
								'controller' => 'setting',
								'action'     => 'view_platform'
							),
							array(
								'id'         => 'edit_topup',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add Banks</span>"),
							array(
								'controller' => 'banks',
								'action'     => 'add'
							),
							array(
								'id'         => 'add_banks',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Banks</span>"),
							array(
								'controller' => 'banks',
								'action'     => 'index'
							),
							array(
								'id'         => 'view_banks',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-user'></i><span class='hidden-tablet'> Staff Members</span>"),
					'#',
					array(
						'id'     => 'slot8',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol  id="sub_menu8" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-bottom:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add Staff Member</span>"),
							array(
								'controller' => 'staff',
								'action'     => 'add'
							),
							array(
								'id'         => 'add_staff',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Staff Members</span>"),
							array(
								'controller' => 'staff',
								'action'     => 'index'
							),
							array(
								'id'         => 'view_staff',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-list-alt'></i><span class='hidden-tablet'> Payments</span>"),
					'#',
					array(
						'id'     => 'slot9',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu9" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;border-bottom:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Payment Notifications</span>"),
							array(
								'controller' => 'payments',
								'action'     => 'payment_notifications'
							),
							array(
								'id'         => 'payment_notifications',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Payment History</span>"),
							array(
								'controller' => 'payments',
								'action'     => 'payment_history'
							),
							array(
								'id'         => 'payment_history',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>

		<!-- Sidebar for Supervisor profile -->

		<?php
			} else if ($this->Session->read('admin_type') == 2) {
		?>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-user'></i><span class='hidden-tablet'> Users</span>"),
					'#',
					array(
						'id'      => 'slot0',
						'class'   => 'ajax-link',
						'escape'  => false
					)
				);
			?>
			<ol id="sub_menu0" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add User</span>"),
							array(
								'controller' => 'user',
								'action'     => 'add'
							),
							array(
								'id'         => 'add',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Users</span>"),
							array(
								'controller' => 'user',
								'action'     => 'index'
							),
							array(
								'id'         => 'view',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-user'></i><span class='hidden-tablet'> Resellers</span>"),
					'#',
					array(
						'id'     => 'slot2',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu2" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add Reseller</span>"),
							array(
								'controller' => 'reseller',
								'action'     => 'add'
							),
							array(
								'id'         => 'add_reseller',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Resellers</span>"),
							array(
								'controller' => 'reseller',
								'action'     => 'index'
							),
							array(
								'id'         => 'view_reseller',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-list-alt'></i><span class='hidden-tablet'> Reports</span>"),
					'#',
					array(
						'id'     => 'slot3',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu3" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Direct Sales</span>"),
							array(
								'controller' => 'report',
								'action'     => 'direct_sales'
							),
							array(
								'id'         => 'direct_sales',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'>  Reseller Sales</span>"),
							array(
								'controller' => 'report',
								'action'     => 'reseller_sales'
							),
							array(
								'id'         => 'reseller_sales',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Transactions</span>"),
							array(
								'controller' => 'report',
								'action'     => 'transactions'
							),
							array(
								'id'         => 'transactions',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> User Purchases</span>"),
							array(
								'controller' => 'report',
								'action'     => 'user_purchases'
							),
							array(
								'id'         => 'user_purchases',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Reseller Purchases</span>"),
							array(
								'controller' => 'report',
								'action'     => 'reseller_purchases'
							),
							array(
								'id'         => 'reseller_purchases',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-star-empty'></i><span class='hidden-tablet'> Inventory</span>"),
					'#',
					array(
						'id'     => 'slot4',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu4" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Inventory</span>"),
							array(
								'controller' => 'inventory',
								'action'     => 'index'
							),
							array(
								'id'         => 'inventory',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-info-sign'></i><span class='hidden-tablet'> Recharge Status</span>"),
					'#',
					array(
						'id'     => 'slot6',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu6" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Check Recharge</span>"),
							array(
								'controller' => 'recharge',
								'action'     => 'status'
							),
							array(
								'id'         =>'rechage_status',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Failed Recharges</span>"),
							array(
								'controller' => 'recharge',
								'action'     => 'failed'
							),
							array(
								'id'         => 'rechage_failed',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-list-alt'></i><span class='hidden-tablet'> Payments</span>"),
					'#',
					array(
						'id'     => 'slot9',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu9" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;border-bottom:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Payment Notifications</span>"),
							array(
								'controller' => 'payments',
								'action'     => 'paymentRequests'
							),
							array(
								'id'         => 'payment_request',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Payment History</span>"),
							array(
								'controller' => 'payments',
								'action'     => 'paymentHistory'
							),
							array(
								'id'         => 'payment_history',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<!-- Sidebar for Customer Service profile -->
		<?php
			} else if($this->Session->read('admin_type') == 1) {
		?>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-user'></i><span class='hidden-tablet'> Users</span>"),
					'#',
					array(
						'id'      => 'slot0',
						'class'   => 'ajax-link',
						'escape'  => false
					)
				);
			?>
			<ol id="sub_menu0" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add User</span>"),
							array(
								'controller' => 'user',
								'action'     => 'add'
							),
							array(
								'id'         => 'add',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Users</span>"),
							array(
								'controller' => 'user',
								'action'     => 'index'
							),
							array(
								'id'         => 'view',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-user'></i><span class='hidden-tablet'> Resellers</span>"),
					'#',
					array(
						'id'     => 'slot2',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu2" class="sub_menu_div" style="display:none;list-style:none;background-color: white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Add Reseller</span>"),
							array(
								'controller' => 'reseller',
								'action'     => 'add'
							),
							array(
								'id'         => 'add_reseller',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> View Resellers</span>"),
							array(
								'controller' => 'reseller',
								'action'     => 'index'
							),
							array(
								'id'         => 'view_reseller',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-info-sign'></i><span class='hidden-tablet'> Recharge Status</span>"),
					'#',
					array(
						'id'     => 'slot6',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu6" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Check Recharge</span>"),
							array(
								'controller' => 'recharge',
								'action'     => 'status'
							),
							array(
								'id'         =>'rechage_status',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Failed Recharges</span>"),
							array(
								'controller' => 'recharge',
								'action'     => 'failed'
							),
							array(
								'id'         => 'rechage_failed',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<li class="left_menu_option">
			<?php
				echo $this->Html->link(
					__("<i class='icon-list-alt'></i><span class='hidden-tablet'> Payments</span>"),
					'#',
					array(
						'id'     => 'slot9',
						'class'  => 'ajax-link',
						'escape' => false
					)
				);
			?>
			<ol id="sub_menu9" class="sub_menu_div" style="display:none;list-style:none;background-color:white;
				margin:0px;padding-left: 13px;border-left:1px solid #ddd;border-right:1px solid #ddd;border-bottom:1px solid #ddd;">
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Payment Notifications</span>"),
							array(
								'controller' => 'payments',
								'action'     => 'paymentRequests'
							),
							array(
								'id'         => 'payment_request',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
				<li class="submenu_option">
					<?php
						echo $this->Html->link(
							__("<i class='icon-circle-arrow-right'></i><span class='hidden-tablet'> Payment History</span>"),
							array(
								'controller' => 'payments',
								'action'     => 'paymentHistory'
							),
							array(
								'id'         => 'payment_history',
								'class'      => 'ajax-link',
								'escape'     => false
							)
						);
					?>
				</li>
			</ol>
		</li>
		<?php
			}
		?>
	</ul> 
</div>
<!-- left menu ends -->

<!-- This controls the submenu highlighting -->
<script>
	var crt_control = '<?php echo $this->request->params['controller']; ?>';
	var crt_action = '<?php echo $this->request->params['action']; ?>';
	var selColor = '#194964';
	
	switch (crt_control) {

		// No menu selected
		case 'cpanel':
			var element = document.getElementById("slot10");
			element.parentNode.classList.add("active");
			break;

		// Users menu
		case 'user':
			var element = document.getElementById("slot0");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu0")).style.display = 'block';
			
			if (crt_action == 'admin_add') {
				(document.getElementById("add_user")).style.color = selColor;
			} else {
				(document.getElementById("view_user")).style.color = selColor;
			}
			break;

		// Sponsors menu
		case 'sponsor':
			var element = document.getElementById("slot1");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu1")).style.display = 'block';
			
			if (crt_action == 'admin_add') {
				(document.getElementById("add_sponsor")).style.color = selColor;
			} else {
				(document.getElementById("view_sponsor")).style.color = selColor;
			}
			break;

		// Resellers menu	
		case 'reseller':
			var element = document.getElementById("slot2");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu2")).style.display = 'block';
			
			if (crt_action == 'admin_add') {
				(document.getElementById("add_reseller")).style.color = selColor;
			} else {
				(document.getElementById("view_reseller")).style.color = selColor;
			}
			break;

		// Reports menu
		case 'report':
			var element = document.getElementById("slot3");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu3")).style.display = 'block';
			
			if (crt_action == 'admin_direct_sales') {
				(document.getElementById("direct_sales")).style.color = selColor;
			} else if (crt_action == 'admin_reseller_sales') {
				(document.getElementById("reseller_sales")).style.color = selColor;
			} else if (crt_action == 'admin_transactions') {
				(document.getElementById("transactions")).style.color = selColor;
			} else if (crt_action == 'admin_user_purchases') {
				(document.getElementById("user_purchases")).style.color = selColor;
			} else if (crt_action == 'admin_reseller_purchases') {
				(document.getElementById("reseller_purchases")).style.color = selColor;
			}
			break;

		// Inventory menu
		case 'inventory':
			var element = document.getElementById("slot4");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu4")).style.display = 'block';
			
			if (crt_action == 'admin_index') {
				(document.getElementById("inventory")).style.color = selColor;
			} else {
				(document.getElementById("inventory_warning")).style.color = selColor;
			}
			break;

		// Rewards menu
		case 'reward':
			var element = document.getElementById("slot5");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu5")).style.display = 'block';
			
			if (crt_action == 'admin_index' || crt_action == 'admin_edit') {
				(document.getElementById("view_reward")).style.color = selColor;
			} else if(crt_action == 'admin_add') {
				(document.getElementById("add_reward")).style.color = selColor;
			}
			break;

		// Recharge menu
		case 'recharge':
			var element = document.getElementById("slot6");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu6")).style.display = 'block';
			
			if (crt_action == 'admin_status') {
				(document.getElementById("rechage_status")).style.color = selColor;
			} else {
				(document.getElementById("rechage_failed")).style.color = selColor;
			}
			break;

		// Settings menu
		case 'setting':
			var element = document.getElementById("slot7");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu7")).style.display = 'block';
			
			if (crt_action == 'admin_edit_points') {
				(document.getElementById("view_setting")).style.color = selColor;
			} else if(crt_action == 'admin_tax') {
				(document.getElementById("edit_tax")).style.color = selColor;
			} else if(crt_action == 'admin_operator') {
				(document.getElementById("edit_operator")).style.color = selColor;
			} else if(crt_action == 'admin_reseller') {
				(document.getElementById("reseller_setting")).style.color = selColor;
            } else if(crt_action=='admin_view_platform')
				(document.getElementById("edit_topup")).style.color = selColor;

		// Banks menu
		case 'banks':
			var element = document.getElementById("slot7");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu7")).style.display = 'block';
			
			if (crt_action =='admin_index' || crt_action == 'admin_edit') {
				(document.getElementById("view_banks")).style.color = selColor;
			} else if (crt_action =='admin_add') {
				(document.getElementById("add_banks")).style.color = selColor;
			}
			break;

		// Staff menu
		case 'staff':
			var element = document.getElementById("slot8");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu8")).style.display = 'block';
			
			if (crt_action =='admin_index' || crt_action == 'admin_edit') {
				(document.getElementById("view_staff")).style.color = selColor;
			} else if (crt_action =='admin_add') {
				(document.getElementById("add_staff")).style.color = selColor;
			}
			break;

		// Payments menu
		case 'payments':
			var element = document.getElementById("slot9");
			element.parentNode.classList.add("active");
			(document.getElementById("sub_menu9")).style.display = 'block';
			
			if (crt_action == 'admin_payment_notifications') {
				(document.getElementById("payment_notifications")).style.color = selColor;
			} else {
				(document.getElementById("payment_history")).style.color = selColor;
			}
			break;
	}
</script>
