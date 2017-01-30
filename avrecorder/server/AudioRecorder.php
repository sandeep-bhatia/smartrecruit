<?php
 session_start(); 
//TODO integrate with the actual user token
 $_SESSION['user'] = uniqid();
 $_SESSION['frameIndex'] = 0;
?>
<html>
	<head>

		<title>AudioRecorder</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">
		html, body { height:100%; background-color: #cc9900;}
		body { margin:0; padding:0; overflow:hidden; }
		#flashContent { width:100%; height:100%; }
		</style>
	</head>
	<body>
		<div id="flashContent">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="300" height="220" id="AudioRecorder" align="middle">
				<param name="movie" value="AudioRecorder.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#cc9900" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<!--[if !IE]>-->
				</object>
				<object type="application/x-shockwave-flash" data="AudioRecorder.swf" width="300" height="220">
					<param name="movie" value="AudioRecorder.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#cc9900" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>
	</body>
</html>
