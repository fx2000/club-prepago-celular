<?php
/**
 * Update Favorite
 *
 * Club Prepago API
 *
 * @copyright     Copyright (c) Móviles de Panamá, S.A. (http://www.movilesdepanama.com)
 * @link          http://www.clubprepago.com Club Prepago Celular(tm) Project
 * @package       API.UpdateFavorite
 * @since         Club Prepago Celular(tm) v 1.0.0
 */
 ?>
<!DOCTYPE html>
<html>
<body>
<form name="API.UpdateFavorite" id="API.UpdateFavorite"  enctype="multipart/form-data" method="post" action="JSON.UpdateFavorite.php" accept-charset="utf-8">	
	<table>
		<TR><TD>Favorite ID</TD><TD><input name="FavoriteId" type="number"></TD></TR>
		<TR><TD>Name</TD><TD><input name="Name" type="text"></TD></TR>
		<TR><TD>Phone Number</TD><TD><input name="Phone_Number" type="text"></TD></TR>
		<TR><TD>Mobile Operator</TD><TD><input name="Operator" type="number"></TD></TR>
		<TR><TD>User ID</TD><TD><input name="UserId" type="number"></TD></TR>
		<TR><TD>Device ID</TD><TD><input name="DeviceId" type="number"></TD></TR>
		<TR><TD>Platform ID</TD><TD><input name="PlatformId" type="number"></TD></TR>
	</table>
	<div class="submit"><input type="submit" value="Submit"></div>							
</form>	
</body>
</html>
