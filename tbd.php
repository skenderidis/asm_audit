<?php

//$array("policies_num"=>2, name)
//    header('HTTP/1.1 500 Internal Server Error');
  //  exit(0);
sleep(1);


$initial_array = [];
$initial_array[] = ['alert' => 'alert000', 'email' => 'Test111'];
$initial_array[] = ['alert' => 'alert333', 'email' => 'Test222'];
$my_array = ['var1' => '111', 'var2' => '222', 'items' => $initial_array];

//print_r($my_array['items'][1]);
echo json_encode($my_array)."\n";
?>


