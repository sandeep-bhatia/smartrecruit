<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Think Triangle Video Player</title>	
	</head>
<body>
<video width="300" height="200" id="thinktriangleplayer" type="video/mp4" controls="controls" src='http://localhost/50101564a336a/video.mp4'></video>
<input type="text" id="annotate" >
<script src="build/mediaelement.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.js"></script>
<script>
$(document).ready(function() {
  // Handler for .ready() called.
  document.getElementById('annotate')['onfocus'] = function() {

		window.currentplayer.pause();
  };
  
   // Handler for .ready() called.
  document.getElementById('annotate')['onblur'] = function() {

		var time = window.currentplayer.currentTime;
		var text = document.getElementById('annotate').value;
		var data = 'time=' + encodeURIComponent(time) + '&data=' + encodeURIComponent(text);  

					
			var i = 0;
			$.ajax({  
					 type: "POST",  
					 url: "http://localhost/player/annotatevideo.php",  
      			     cache: false, 
					 data: data,  
					 success: function(html) {  
					 	
					 } 
				  });  
		  window.currentplayer.play();

  };	



});

MediaElement('thinktriangleplayer', {success: function(me) {
	//me.src = 'http://localhost/500e3797b3246/video.mp4';
	me.play();
	window.currentplayer = me;
	
	me.addEventListener('timeupdate', function() {
		//make a ajax request and see if we have some annotations that are stored
		
		$.get('http://localhost/player/readannotations.php', 'time=' + me.currentTime, function(data) {
		document.getElementById('annotate').value = data;
		});

	}, false);

}});
</script>
</body>
</html>