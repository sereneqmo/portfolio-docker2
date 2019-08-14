<body>
    <?php 
	$ip_server = file_get_contents("http://ipecho.net/plain");
	echo "Server IP Address is: $ip_server";  
   ?>
</body>