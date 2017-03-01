<?php
/**
 * Send Payment notification (Bank deposit)
 *
 * Club Prepago API
 *
 * All taxes and discount rates for direct deposit payments are calculated by the
 * Payments controller once the payment notification is approved
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.SendPaymentDeposit
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
 ?>
<!DOCTYPE html>
<html>
<body>
<form name="API.SendPaymentDeposit" id="API.SendPaymentDeposit" enctype="multipart/form-data" method="post" action="JSON.SendPaymentDeposit.php" accept-charset="utf-8">	
	<table>
		<TR><TD>User ID</TD><TD><input name="UserId" type="number"></TD></TR>
		<TR><TD>Device ID</TD><TD><input name="DeviceId" type="number"></TD></TR>
		<TR><TD>Platform ID</TD><TD><input name="PlatformId" type="number"></TD></TR>
		<TR><TD>Amount</TD><TD><input name="amount" type="number" step="0.01"></TD></TR>
		<TR><TD>Bank ID</TD><TD><input name="BankId" type="number"></TD></TR>
		<TR><TD>Reference Number</TD><TD><input name="reference_number" type="text"></TD></TR>
		<TR><TD>Latitude</TD><TD><input name="latitude" type="text"></TD></TR>
		<TR><TD>Longitude</TD><TD><input name="longitude" type="text"></TD></TR>
	</table>
	<div class="submit"><input type="submit" value="Submit"></div>							
</form>	
</body>
</html>
