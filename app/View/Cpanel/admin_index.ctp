<?php
/**
 * Sign in screen
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Cpanel
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			echo $this->Html->meta(
				'icon',
				'',
				array('type' => 'icon')
			);
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=9"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Club Prepago Celular">
		<meta name="author" content="Daniel Duque">
		<?php echo $this->Html->charset(); ?>
		<title><?php echo __('Club Prepago Celular'); ?></title>
		<?php
			echo $this->Html->css('bootstrap-cerulean');
			echo $this->Html->css('bootstrap-responsive');
			echo $this->Html->css('admin_style');
			echo $this->Html->script('livevalidation_standalone');
		?>
	</head>
	<body>
		<div class="well well-lg" style="background-color: #007e88;">
       <div class="" style="color:white; margin-left: .5cm;">Venezuela |
		      <?php echo $this->Html->image('veneflag.png', array('width'=>'16px','margin-left'=>'.5cm'));?>
			 </div>
    </div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="row-fluid">
					<div class="span12 center login-header">
						<?php
							echo $this->Html->link(
								$this->Html->image('logo.png'),
								array(
									'controller' => 'cpanel',
									'action'     => 'admin_index'
								),
								array(
									'style'      => 'width: auto;',
									'escape'     => false
								)
							);
						?>
					</div>
				</div>
				<div class="row-fluid" style="margin-top:40px;">
					<div class="well span5 center login-box" style="padding:15px;">
						<div class="alert alert-info">
							<?php echo __('Enter your username and password'); ?>
						</div>
						<div class="row-fluid" style="margin:0px 0px 10px 0px;">
							<?php
								echo $this->Session->read('alert');
								$_SESSION['alert'] = "";
							?>
						</div>
						<?php
							echo $this->Form->create(
								'',
								array(
									'url'   => array(
										'controller' => 'cpanel',
										'action'     => 'admin_index'
									),
									'class' => 'form-horizontal'
								)
							);
						?>
						<fieldset>
							<div class="input-prepend" title=<?php echo __('Username'); ?> data-rel="tooltip">
								<span class="add-on"><i class="icon-user"></i></span>
								<?php
									echo $this->Form->input(
										'Admin.username',
										array(
											'type'  => 'text',
											'class' => 'input-large span10',
											'id'    => 'username',
											'div'   => false,
											'label' => false
										)
									);
								?>
								<script language = "javascript" type = "text/javascript">
									var f1 = new LiveValidation('username');
									f1.add( Validate.Presence);
								</script>
							</div>
							<div class="clearfix"></div>
							<div class="input-prepend" title=<?php echo __('Password'); ?> data-rel="tooltip">
								<span class="add-on"><i class="icon-lock"></i></span>
								<?php
									echo $this->Form->input(
										'Admin.password',
										array(
											'type'  => 'password',
											'class' => 'input-large span10',
											'id'    => 'password',
											'div'   => false,
											'label' => false
										)
									);
								?>
								<script language = "javascript" type = "text/javascript">
									var f1 = new LiveValidation('password');
									f1.add( Validate.Presence);
								</script>
							</div>
							<div class="clearfix"></div>
							<div class="span6"></div>
							<div class="clearfix"></div>
							<div class="center span2">
								<?php
									echo $this->Form->Submit(
										__('Sign in'),
										array(
											'class'=>'btn btn-primary'
										)
									)
								?>
							</div>
						</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
			echo $this->Html->script('jquery-1.7.2.min');
			echo $this->Html->script('jquery-ui-1.8.21.custom.min');
			echo $this->Html->script('bootstrap-transition');
			echo $this->Html->script('bootstrap-alert');
			echo $this->Html->script('bootstrap-modal');
			echo $this->Html->script('bootstrap-dropdown');
			echo $this->Html->script('bootstrap-scrollspy');
			echo $this->Html->script('bootstrap-tab');
			echo $this->Html->script('bootstrap-tooltip');
			echo $this->Html->script('bootstrap-popover');
			echo $this->Html->script('bootstrap-button');
			echo $this->Html->script('bootstrap-collapse');
			echo $this->Html->script('bootstrap-carousel');
			echo $this->Html->script('bootstrap-typeahead');
			echo $this->Html->script('bootstrap-tour');
			echo $this->Html->script('jquery.cookie');
			echo $this->Html->script('fullcalendar.min');

			// 	<!-- data table plugin -->
			echo $this->Html->script('jquery.dataTables.min');

			// 	<!-- chart libraries start -->
			echo $this->Html->script('excanvas');
			echo $this->Html->script('jquery.flot.min');
			echo $this->Html->script('jquery.flot.pie.min');
			echo $this->Html->script('jquery.flot.stack');
			echo $this->Html->script('jquery.chosen.min');
			echo $this->Html->script('jquery.flot.resize.min');
			// 	<!-- chart libraries end -->

			// 	<!-- select or dropdown enhancer -->
			echo $this->Html->script('jquery.uniform.min');

			// 	<!-- checkbox, radio, and file input styler -->
			echo $this->Html->script('jquery.colorbox.min');

			// 	<!-- rich text editor library -->
			echo $this->Html->script('jquery.cleditor.min');

			// 	<!-- notification plugin -->
			echo $this->Html->script('jquery.noty');

			// 	<!-- file manager library -->
			echo $this->Html->script('jquery.elfinder.min');

			// 	<!-- star rating plugin -->
			echo $this->Html->script('jquery.raty.min');

			// 	<!-- for iOS style toggle switch -->
			echo $this->Html->script('jquery.iphone.toggle');

			// 	<!-- autogrowing textarea plugin -->
			echo $this->Html->script('jquery.autogrow-textarea');

			// 	<!-- multiple file upload plugin -->
			echo $this->Html->script('jquery.uploadify-3.1.min');

			// 	<!-- history.js for cross-browser state change on ajax -->
			echo $this->Html->script('jquery.history');

			// 	<!-- application script for Charisma demo -->
			echo $this->Html->script('travel_script');
		?>
	</body>
</html>
