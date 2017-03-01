<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo __('Error'); ?>:
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('bootstrap-cerulean.css');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
	<br/><br/><br/><br/>
		<div id="content">
		<?php
			echo '<center><a href="' . Router::url('/', true) . '"><img src="' . Router::url('/img/', true) . 'logo.png" alt="Club Prepago Celular"></a></center>';
		?>
		<br/><br/>
		<br/><br/>
		<?php
			echo __(
				'<center>
					<div style="font-family:Tahoma;">
						<h2>Something went wrong...</h2>
						</br>
						</br>
						If you have any problems, please contact <a href="mailto:support@clubprepago.com">support@clubprepago.com</a>
						</br>
						or call us at <b>+507 388-6220</b>
					</div>
				</center>'
			);
		?>
		</div>
	</div>
</body>
</html>
