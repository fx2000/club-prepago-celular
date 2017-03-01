<?php
/**
 * Get Reward Points
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.GetRewardPoints
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
?>
<!DOCTYPE html>
<html>
<body>
<form name="API.GetRewardPoints" id="API.GetRewardPoints"  enctype="multipart/form-data" method="post" action="JSON.GetRewardPoints.php" accept-charset="utf-8">	
	<table>
		<TR><TD>User ID</TD><TD><input name="UserId" type="text"></TD></TR>
		<TR><TD>Device ID</TD><TD><input name="DeviceId" type="text"></TD></TR>
		<TR><TD>Platform ID</TD><TD><input name="PlatformId" type="text"></TD></TR>
	</table>
	<div class="submit"><input type="submit" value="Submit"></div>							
</form>	
</body>
</html>
