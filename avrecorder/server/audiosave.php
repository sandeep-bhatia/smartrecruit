//*****************************************************************************************************************************************************
//Copyright © Think Triangle Oyc
//Author : Sandeep Bhatia
//Reviewers : (None)
//Last Update Description : Initial File Creation done. saves the audio file and uses ffmpeg to process and create the final video
//*****************************************************************************************************************************************************/
<?php
session_start(); 
$folderid = $_SESSION['user'];
if ( isset ( $GLOBALS["HTTP_RAW_POST_DATA"] )) {
	try
	{
		//configuration variable
		//TODO : introduce a common configuration files and read the configuration params from that variable.
		$frameRate = 23;
		$bitRate = 9600;
		
		//Get the time stamp and use it in audio
	    $uniqueStamp = date(U);
	    $filename = $folderid."\\audio".$uniqueStamp.".wav";
	    $fp = fopen( $filename,"a+b");
	    fwrite( $fp, $GLOBALS[ 'HTTP_RAW_POST_DATA' ] ); 
	    fclose( $fp );
	    
	    //ffmpeg commands
	    //these commands would be initially made by the OpenCV and ffmpeg integration of our libraries
	 	$command = "ffmpeg -qscale 1 -r $frameRate -b $bitRate -i  $folderid\\image%d.png -i  $filename -vcodec libx264 -acodec libfaac $folderid\\video.mp4";	
	 	exec($command);
	    echo "filename=".$filename."&base=".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"]);
    }
    catch(Exception $error)
    {
    	//TODO : redirect to the common error page here
    }
}
