<html>
<head>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="shortcut icon" sizes="1024x1024" href="icons/1024.png">
	<link rel="shortcut icon" sizes="128x128" href="icons/128.png">
    <link rel="apple-touch-icon" href="icons/60.png" />
	<link rel="apple-touch-icon" sizes="76x76" href="icons/76.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="icons/120.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="icons/152.png" />
    
    <title>La porte!</title>
    
	<style type="text/css">
		img {
			position: absolute;
			top: 50%;
			left: 50%;
			width: 1in;
			height: 1in;
			margin-top: -0.5in; /* Half the height */
			margin-left: -0.5in; /* Half the width */
		}
    </style>
</head>
<body onLoad="window.close();">
<?php

/* -- CONFIGURATION -- */

// The device's IP address
$address = "192.168.1.100";

// The device's port
$service_port = 2000;

// The SHA-256 password hash (Generate it on http://www.xorbin.com/tools/sha256-hash-calculator)
$password_hash = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

// The command to switch the LazyBone's relay to the ON position
$cmd_pre = "e";

// The command to switch the LazyBone's relay to the OFF position
$cmd_post = "o";

// The delay between the on and off position, in seconds
$cmd_delay = 1;

/* -- */

function writeCommand($socket, $command) {
	socket_write($socket, $command, strlen($command));
}

function readCommand($socket, $command) {
	$out = '';
	while ($out = socket_read($socket, strlen($command))) {}
}

$password = $_GET["p"];

if (hash("sha256", $password) == $password_hash) {
	/* Create a TCP/IP socket. */
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket === false) {
		echo "socket_create() failed.<br/>\n";
	}
	
	$result = socket_connect($socket, $address, $service_port);
	if ($result === false) {
		echo "socket_connect() failed.<br/>\n";
	}
	
	socket_set_nonblock($socket);
	
	readCommand($socket, "PASS?");
	writeCommand($socket, $password);
	readCommand($socket, "AOK");
	writeCommand($socket, $cmd_pre);
	sleep($cmd_delay);
	writeCommand($socket, $cmd_post);
	
	socket_close($socket);
}
?>
<img src="lock.png" onclick="location.reload();" />
</body>
</html>