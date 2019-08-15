<body>
    <?php 
    //cannot use the $_SERVER since rn we r in docker so for this we can only get the ip address
    //of my docker container in this machine
	$ip_server = file_get_contents("http://ipecho.net/plain");
	echo "Server IP Address is: $ip_server";  
   ?>
</body>