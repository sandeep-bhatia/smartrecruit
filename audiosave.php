//*****************************************************************************************************************************************************
//Copyright � Think Triangle Oyc
//Author : Sandeep Bhatia
//Reviewers : (None)
//Last Update Description : Initial File Creation done. saves the audio file and uses ffmpeg to process and create the final video
//*****************************************************************************************************************************************************/
<?php
session_start(); 
$folderid = $_SESSION['user'];
$frameRate = $_SESSION['fps'];
if ( isset ( $GLOBALS["HTTP_RAW_POST_DATA"] )) {
	try
	{
	 $uniqueStamp = date(U);
	 $filename = $folderid."\\audio".$uniqueStamp.".wav";
	 $fp = fopen( $filename,"a+b");
	 fwrite( $fp, $GLOBALS[ 'HTTP_RAW_POST_DATA' ] ); 
	 fclose( $fp );
	 $command = "ffmpeg -qscale 1 -r $frameRate -i  $folderid\\image%d.png -i  $filename -vcodec libx264 -acodec libfaac $folderid\\video.mp4";	
	 exec($command);
	 echo "filename=".$filename."&base=".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"]);
    }
    catch(Exception $error)
    {
    	//TODO : redirect to the common error page here
    }
}
