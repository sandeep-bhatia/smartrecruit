<?php 
include("include/common.php");
?>

<html>
<body>
	<H1>Welcome to smart recruiting online platform.</H1><br>
	Please use LinkedIn credentials to access the system<br><br><br>
	Please click on the image.<br>  
	<?php 
	echo "Hello World from login.";
	logToFile("First request to the site. Sending user to login page");
	?>
	
	<a href="view/controller.php?PAGE_ID=AUTHENTICATION">
	<img src="linkedin.png"/>
	</a>
</body>
</html>

