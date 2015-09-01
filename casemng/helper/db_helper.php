<?php
function sql_execute($ipaddr, $db, $user, $pwd, $sql) {
	$con = mysql_connect($ipaddr, $user, $pwd);
	if (!$con) {
		echo('Could not connect: ' . mysql_error());
	}
	mysql_select_db($db, $con);
	mysql_query($sql);
	mysql_close($con);
}
