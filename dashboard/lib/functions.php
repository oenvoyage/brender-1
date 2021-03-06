<?php

function d_print($string_to_print){
	if ($GLOBALS['debug'] == True) {
		echo "<div>" . $string_to_print . "</div>";
	} else {
		return;
	}
}


function match_extension($route, $extension) {
	if (substr_compare($route, $extension, -strlen($extension), strlen($extension)) === 0) {
		return True;
	} else {
		return False;
	}
}


function ask_server_legacy($arg_1) {
	$address = $GLOBALS['address'];
	$service_port = $GLOBALS['service_port'];

    /* Create a TCP/IP socket. */
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket === false) {
		d_print("socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
	} else {
		d_print("OK");
	}

	# echo "Attempting to connect to '$address' on port '$service_port'...";
	$result = socket_connect($socket, $address, $service_port);
	if ($result === false) {
		d_print("socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n");
	} else {
		d_print("OK");
	}

	$in = $arg_1;
	$out = '';

	d_print("Sending HTTP HEAD request...");
	socket_write($socket, $in, strlen($in));
	d_print("OK");

	d_print("Reading response:");
	
	$x = 1;
	while ($x == 1) {
		$out = socket_read($socket, 2048);
		echo $out;
		$x = 2;
	}
	
	d_print("Closing socket...");
	socket_close($socket);
	d_print("OK");
}


function ask_server($request) {

	$address = $GLOBALS['address'];
	$service_port = $GLOBALS['service_port'];

	$socket = stream_socket_client("tcp://$address:$service_port", $errno, $errstr, 30);
	$request = $request . "\n";

	if (!$socket) {
	    echo "$errstr ($errno)<br />\n";
	} else {

	    fwrite($socket, $request);
	    while (!feof($socket)) {
	        echo fgets($socket, 512); 
	    }
	    fclose($socket);
	}
}


function is_current_page($current_page, $page_name) {
	if ($current_page == $page_name) {
		echo 'class="active"';
	} 
}

?>