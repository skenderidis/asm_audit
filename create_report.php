<?php


if (isset($_GET["report"])) {
    if($_GET["report"]=="asm")
	{
		$command = escapeshellcmd('python3 create_asm.py');
		$output = shell_exec($command);
//		sleep(8);
		if(!(strpos($output, 'ok') !== false))
		{
			 header('HTTP/1.1 501 Script Error');
		}
	}
}
else{  
    header('HTTP/1.1 500 No report set');
}

?>