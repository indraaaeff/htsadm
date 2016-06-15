<?php
	$username = 'iis_client';
	$server= 'report.hts.net.id';
	$Database = new mysqli( $server, $username, '', 'iis_data_demo' );
	if ($Database->connect_error) {
		die( 'Connect Error (' . $Database->connect_errno . '). '
			. $Database->connect_error );
	}
?>