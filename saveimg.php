//*****************************************************************************************************************************************************
//Copyright © Think Triangle Oyc
//Author : Sandeep Bhatia
//Reviewers : (None)
//Last Update Description : Initial File Creation done. Accumulates the video frames
//*****************************************************************************************************************************************************/
<?php
session_start(); 
if (isset ( $GLOBALS["HTTP_RAW_POST_DATA"] )) {
	try
	{

		 //TODO integrate with the actual user token
		$user = $_SESSION['user'];
		$frameIndex = $_SESSION['frameIndex'];
	   	$_SESSION['frameIndex'] = $_SESSION['frameIndex'] + 1;
	    
	   	if(!is_dir($_POST['guid']))
		{
				mkdir($user);
		}
		
		$filename = $user."/image".$frameIndex.".png";
	    $fp = fopen($filename,"wb");
	    fwrite( $fp, $GLOBALS[ 'HTTP_RAW_POST_DATA' ] ); 
	    fclose( $fp );
	
	    echo "filename=".$filename."&base=".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"]);
	}
	catch(Exception $error)
	{
	}
}
