<?php
/**
 * Login
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.Login
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<!DOCTYPE html>
<html>
<body>
<form name="API.Login" id="API.Login" enctype="multipart/form-data" method="post" action="JSON.Login.php" accept-charset="utf-8">	
	<table>
		<TR><TD>Email Address</TD><TD><input name="Email" type="text"></TD></TR>
		<TR><TD>Password</TD><TD><input name="Password" type="text"></TD></TR>
		<TR><TD>Device ID</TD><TD><input name="DeviceId" type="number"></TD></TR>
		<TR><TD>Platform ID</TD><TD><input name="PlatformId" type="number"></TD></TR>
	</table>
	<div class="submit"><input type="submit" value="Submit"></div>
</form>
</body>
</html>
