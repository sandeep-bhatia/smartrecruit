<?php
	if (isset($_POST['time'])) {
		$fopen = fopen("annotations.txt", "a+");
		fwrite($fopen, $_POST['time']);
		fwrite($fopen, ":");
		fwrite($fopen, $_POST['data']);
		fwrite($fopen, "$");
		fclose($fopen);
	}
?>
