<?php
	$dir = "Z:\\denwer\\www\\denwer\\font-generator\\fonts";
	
	$dh  = opendir($dir);
	
	while (false !== ($filename = readdir($dh))) 
	{
		$files[] = $filename;
	}
	
	die(var_dump($files));
?>

<?php
	$aaa = exec("ttf2ufm -a -F times.ttf");

die(var_dump($aaa));
?>