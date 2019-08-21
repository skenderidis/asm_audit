<?php

	if (!isset($_POST["bigip_ip"]) ||  !isset($_POST["username"]) || !isset($_POST["password"]) ||  !isset($_POST["name"]) || !isset($_POST["id"]) || !isset($_POST["type"]))
	{
		header('HTTP/1.1 502 No variables Set');
		exit();
	}

	$policy_name = 'python3 collect.py ' . $_POST["bigip_ip"] . ' ' . $_POST["username"] . ' ' . $_POST["password"] . ' ' . $_POST["name"] . ' ' . $_POST["id"] . ' ' .$_POST["type"];
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