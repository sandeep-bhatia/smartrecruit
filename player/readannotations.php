<?php
	try
	{
		if (isset($_GET['time'])) {
			$currenttime  = $_GET['time'];
			if(file_exists("annotations.txt"))
			{
				//test code just to POC this annotation data should go in MYSQL
				$data = file_get_contents("annotations.txt");
				if(strlen($data) > 0)
				{
						$stringarray=explode('$',$data);  
						if(array_count_values($stringarray) > 0)
						{
							foreach ($stringarray as $key => $value) {  
								$stringmap = explode(':', $value);
								if(array_count_values($stringmap) > 0)
								{
									if(($stringmap[0] - $currenttime < 1) && ($stringmap[0] - $currenttime > -1))
									{
										echo $stringmap[1];
									}
								}
							}
						} 
				}
				else
				{
					echo "";
				}
			}
		}
	}
	catch(Exception $error)
	{
		echo "";
	}

?>
