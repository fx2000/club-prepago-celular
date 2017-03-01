<?php
/**
 * Admin Layout
 *
 *
 *
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       app.View.Layouts
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->meta('icon', '', array('type' => 'icon'));?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=9"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Club Prepago Celular">
	<meta name="author" content="Daniel Duque">
	<?php echo $this->Html->charset(); ?>

	<!-- Page Title -->
	<title><?php echo __('Club Prepago'); ?></title>
	
	<?php
		echo $this->Html->css('admin_style');
		echo $this->Html->css('bootstrap-cerulean');
		echo $this->Html->css('bootstrap-responsive');
		echo $this->Html->css('fullcalendar');
		echo $this->Html->css('fullcalendar.print');
		echo $this->Html->css('chosen');
		echo $this->Html->css('uniform.default');
		echo $this->Html->css('colorbox');
		echo $this->Html->css('jquery.cleditor');
		echo $this->Html->css('jquery-ui-1.8.21.custom');
		echo $this->Html->css('jquery.noty');
		echo $this->Html->css('noty_theme_default');
		echo $this->Html->css('elfinder.min');
		echo $this->Html->css('elfinder.theme');
		echo $this->Html->css('jquery.iphone.toggle');
		echo $this->Html->css('opa-icons.css');
		echo $this->Html->css('uploadify.css');
		echo $this->Html->script('livevalidation_standalone');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');		
	?>
</head>
<body>
	<noscript>
		<div class="alert alert-block span10">
			<h4 class="alert-heading">Warning!</h4>
			<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
		</div>
	</noscript>
	<div id="container">
		<div id="container">
			<?php echo $this->element('adminheader');?>
		</div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span2 main-menu-span">
					<?php echo $this->element('adminLeftMenu');?>
				</div>
				<div id="content" class="span10">
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->fetch('content'); ?>
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
		echo $this->Html->script('jquery.flot.min');
		echo $this->Html->script('excanvas');
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
	<script>
		function deleteAllCookies() {
			var cookies = document.cookie.split(";");

			for (var i = 0; i < cookies.length; i++) {
				var cookie = cookies[i];
				var eqPos = cookie.indexOf("=");
				var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
				document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
			}
		}
		deleteAllCookies();
	</script>
</body>
</html>
