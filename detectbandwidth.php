<html>
	<head>
		<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
		<script type="text/javascript" src="js/utilities.js"></script>
		<script type="text/javascript" >
		//call our setup method and start recording
		$(document).ready(function() {
			var fps = DetectBandWidth();
			$('#framesVal').val(fps);
	                setTimeout(redirect, 10000);		
			
		});

		function redirect()
		{
			$('#recorderinit').submit();
		}
		</script>
	</head>

	<body>
		<form id="recorderinit" action="AudioRecorder.php" method="POST">
		Computing the frames per second based on bandwith
		<input type='hidden' id='framesVal' name='framesVal'>
		</form>
	</body>
</html>

