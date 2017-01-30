<html>
 <body>
 	<form action="uploadquestions.php" method="post" enctype="multipart/form-data">
		 <input type="file" name="filename"> 
		 <input type="submit" name="submit" value="Submit" >
	</form>
 </body>
</html> 

<?php
	//this is just a sample page telling the usage
	if(isset($_FILES['filename']['tmp_name']))
	{
	// including FileReader
	include( 'FileReader.php' );
	// including CSVReader
	include( 'csvreader.php' );
	
	$folder = 'uploaded/';
	if (is_uploaded_file($_FILES['filename']['tmp_name']))  
	{       
		move_uploaded_file($_FILES['filename']['tmp_name'], $folder.$_FILES['filename']['name']);
	} 
	
	// instancing new CSVReader object reading a file 'sample.csv'
	$readfile = $folder.$_FILES['filename']['name'];
	$reader = new CSVReader(new FileReader($readfile));
	// set the separator use format, here a comma
	$reader->setSeparator( ',' );
	
	// line tracking
	$line = 0;
	//  output
	echo '<table cellpadding=2 cellspacing=1 bgcolor="#cdcdcd" border=0>';
	// while line reading do not return false, otherwise it return an array containing CSV cell.
	while( false != ( $cell = $reader->next() ) )
	{
		if ( $line == 0 )
		{
			echo "<tr>\n";
			echo	"<td style='font: 11px Verdana;font-weight: bold' nowrap> Index </td>\n";
			for ( $i = 0; $i < count( $cell ); $i++ )
			{
				echo	"<td nowrap style='font: 11px Verdana; font-weight: bold'> Cell n°{$i}</td>\n";
			}
			echo "</tr>\n";		
		}
		echo "<tr>\n";
		echo	"<td bgcolor='".( ( $line % 2 ) == 0 ? '#efefef' : '#ffffff'  )."'>{$line}</td>\n";
		for ( $i = 0; $i < count( $cell ); $i++ )
		{
			echo	"<td bgcolor='".( ( $line % 2 ) ==0 ? '#efefef' : '#ffffff'  )."'>{$cell[$i]}</td>\n";
		}
		echo "</tr>\n";
		$line++;
	}
	echo '<table>';
}
?>