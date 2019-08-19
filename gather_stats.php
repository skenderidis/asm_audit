<?php

	if (!isset($_POST["name"]) || !isset($_POST["id"]) || !isset($_POST["type"]))
	{
		header('HTTP/1.1 502 No variables Set');
		exit();
	}

	$policy_name = 'collect.py 10.1.1.11 admin  123!@#qwe ' . $_POST["name"] . ' ' . $_POST["id"] . ' ' .$_POST["type"];
//	echo $policy_name;
	//exit();
	$command = escapeshellcmd($policy_name);
	$output = shell_exec($command);

	if(!(strpos($output, 'ok') !== false))
	{

		header('HTTP/1.1 501 Script Error');
	}
	else
	{ 
		echo ("Policy: ".$_POST["name"]." Parsed Successfully");
	}

?>