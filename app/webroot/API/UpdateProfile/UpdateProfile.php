<?php
/**
 * Update Profile
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.UpdateProfile
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
 ?>
<!DOCTYPE html>
<html>
<body>
<form name="API.UpdateProfile" id="API.UpdateProfile"  enctype="multipart/form-data" method="post" action="JSON.UpdateProfile.php" accept-charset="utf-8">	
	<table>
		<TR><TD>Email</TD><TD><input name="Email" type="text"></TD></TR>
		<TR><TD>Phone Number</TD><TD><input name="Phone_Number" type="text"></TD></TR>
		<TR><TD>Address</TD><TD><textarea name="Address" type="text"></textarea></TD></TR>
		<TR><TD>City</TD><TD><input name="City" type="text"></TD></TR>
		<TR><TD>State or Province</TD><TD><input name="Province" type="text"></TD></TR>
		<TR><TD>User ID</TD><TD><input name="UserId" type="number"></TD></TR>
		<TR><TD>Device ID</TD><TD><input name="DeviceId" type="number"></TD></TR>
		<TR><TD>Platform ID</TD><TD><input name="PlatformId" type="number"></TD></TR>
	</table>
	<div class="submit"><input type="submit" value="Submit"></div>							
</form>	
</body>
</html>