<?php
/**
 * Header
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Elements
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php
				echo $this->Html->link(
					$this->Html->image('logo.png'),
					array(
						'controller' => 'cpanel',
						'action'     => 'admin_home'
					),
					array(
						'class'      => 'brand',
						'style'      => 'width: auto;',
						'escape'     => false
					)
				);
			?>
			
			<!-- user dropdown starts -->
			<div class="btn-group pull-right" >
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-user"></i><span class="hidden-phone"> <?php echo $this->Session->read('admin_name') ?></span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
					  <?php
					  	echo $this->Html->link(
					  		__('Profile'),
					  		array(
					  			'controller' => 'cpanel',
					  			'action'     => 'profile'
					  		)
					  	);
					  ?>
					</li>
					<li class="divider"></li>
					<li>
						<?php
							echo $this->Html->link(
								__('Change Password'),
								array(
									'controller' => 'cpanel',
									'action'     => 'change_password'
								)
							);
						?>
					</li>
					<li class="divider"></li>
					<li>
						<?php
							echo $this->Html->link(
								__('Sign Out'),
								array(
									'controller' => 'cpanel',
									'action'     => 'admin_signout'
								)
							);
						?>
					</li>
				</ul>
			</div>
			<!-- user dropdown ends -->
			
			<div class="top-nav nav-collapse">
				<ul class="nav">
				</ul>
			</div>
		</div>
	</div>
</div>
