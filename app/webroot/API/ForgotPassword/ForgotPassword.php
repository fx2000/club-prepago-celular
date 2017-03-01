<?php
/**
 * Forgot Password
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.ForgotPassword
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<!DOCTYPE html>
<html>
<body>
<form name="API.ForgotPassword" id="API.ForgotPassword" enctype="multipart/form-data" method="post" action="JSON.ForgotPassword.php" accept-charset="utf-8">	
	<table>
		<TD>Email Address</TD><TD><input name="Email" type="text"</TD></TR>
		<TD>Device ID</TD><TD><input name="DeviceId" type="number"></TD></TR>
		<TD>Platform ID</TD><TD><input name="PlatformId" type="number"></TD></TR>
	</table>
	<div class="submit"><input type="submit" value="Submit"></div>
</form>
</body>
</html>
