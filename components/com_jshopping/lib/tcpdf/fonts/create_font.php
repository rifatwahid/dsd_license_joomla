<?php
	require('makefontuni.php');
	
	/*
		exec("ttf2ufm -a -F kberry.ttf");
		sleep(3);
		MakeFont("kberry.ttf",'kberry.ufm','unicode-sample');
		
		die("done");
	*/
	
	$dir = "Z:\\denwer\\www\\denwer\\font-generator";
	
	$dh  = opendir($dir);
	
	while (false !== ($filename = readdir($dh))) 
	{
		$files[] = $filename;
	}
	
	$count = count($files);
	//sort($files);
	//die(var_dump($files));
	
	
	for ($i=3; $i<$count; $i++)
	{
		$fname = explode(".", $files[$i]);
		$name = $fname[0];
		$ext = $fname[1];
		
		//die(var_dump($ext));
		
		if ( $ext != "" &&  ($ext == "TTF" || $ext == "ttf") )
		{
			exec("ttf2ufm -a -F ".$name.".ttf");
			//sleep(10);
		
			MakeFont($name.".ttf", $name.".ufm", "unicode-sample");
			//sleep(10);
		}
	}
	
	echo "done";
?>

