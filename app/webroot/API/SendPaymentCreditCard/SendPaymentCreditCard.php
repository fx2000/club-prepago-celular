<?php
/**
 * Send Credit Card Payment
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.SendPaymentCreditCard
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<!DOCTYPE html>
<html>
<body>
<form name="API.SendPaymentCreditCard" id="API.SendPaymentCreditCard"enctype="multipart/form-data"
	method="post" action="JSON.SendPaymentCreditCard.php" accept-charset="utf-8">
	<table>
		<TR><TD>User ID</TD><TD><input name="UserId" type="number"></TD></TR>
		<TR><TD>Device ID</TD><TD><input name="DeviceId" type="number"></TD></TR>
		<TR><TD>Platform ID</TD><TD><input name="PlatformId" type="number"></TD></TR>
		<TR><TD>Amount</TD><TD><input name="amount" type="number" step="0.01"></TD></TR>
		<TR><TD>Discount Rate</TD><TD><input name="discount_rate" type="number" step="0.01"></TD></TR>
		<TR><TD>Tax Rate</TD><TD><input name="tax_rate" type="number" step="0.01"></TD></TR>
		<TR><TD>Transaction ID</TD><TD><input name="TransactionId" type="text"></TD></TR>
		<TR><TD>Transaction Status</TD><TD><input name="TransactionStatus" type="number"></TD></TR>
		<TR><TD>Latitude</TD><TD><input name="latitude" type="text"></TD></TR>
		<TR><TD>Longitude</TD><TD><input name="longitude" type="text"></TD></TR>
	</table>
	<div class="submit"><input type="submit" value="Submit"></div>
</form>
</body>
</html>
