<?php
/**
 * Recharge with Points
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.RechargePoints
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<!DOCTYPE html>
<html>
<body>
<form name="API.Recharge" id="API.Recharge" enctype="multipart/form-data" method="post" action="JSON.Recharge.php" accept-charset="utf-8">	
	<table>
		<TR><TD>Phone Number</TD><TD><input name="Phone_Number" type="text"></TD></TR>
		<TR><TD>Mobile Operator</TD><TD><input name="Operator" type="number"></TD></TR>
		<TR><TD>Amount</TD><TD><input name="Amount" type="number" step="0.01"></TD></TR>
		<TR><TD>Points</TD><TD><input name="Points" type="number"></TD></TR>
		<TR><TD>Payment Method</TD><TD><input name="Payment_Method" type="number"></TD></TR>
		<TR><TD>User ID</TD><TD><input name="UserId" type="number"></TD></TR>
		<TR><TD>Device ID</TD><TD><input name="DeviceId" type="number"></TD></TR>
		<TR><TD>Platform ID</TD><TD><input name="PlatformId" type="number"></TD></TR>
		<TR><TD>Latitude</TD><TD><input name="latitude" type="text"></TD></TR>
		<TR><TD>Longitude</TD><TD><input name="longitude" type="text"></TD></TR>
	</table>
	<div class="submit"><input type="submit" value="Submit"></div>
</form>
</body>
</html>
