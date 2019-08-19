<?php

function recursiveRemove($dir) {
    $structure = glob(rtrim($dir, "/").'/*');
    if (is_array($structure)) {
        foreach($structure as $file) {
            if (is_dir($file)) recursiveRemove($file);
            elseif (is_file($file)) unlink($file);
        }
    }
    rmdir($dir);
	}

/*
$accesstoken = base64_encode($_POST['user'].':'.$_POST['pass']);
$accesstoken = base64_encode($_POST['user'].':'.$_POST['pass']);
$bigip_ip = $_POST['bigip_ip'];
$error=0;
*/
$accesstoken = 'YWRtaW46MTIzIUAjcXdl';
$bigip_ip = '10.1.1.11';

if ($_POST['delete_policies']=="yes")
{
	$dir = getcwd() . '/config_files/';
	$reports_structure = glob(rtrim($dir, "/").'/*');

	if (is_array($reports_structure)) 
	{
		foreach($reports_structure as $file) 
		{
			if (is_dir($file)) recursiveRemove($file);
			elseif (is_file($file)) unlink($file);
		}
	}		
}

$ch = curl_init();

$url = 'https://'.$bigip_ip .'/mgmt/tm/asm/policies?$select=name,id,type';
$header = array();
$header[] = 'Content-type: application/json';
$header[] = 'Authorization: Basic '.$accesstoken;

curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

//curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

$info = curl_getinfo($ch);

if($errno = curl_errno($ch))
    $error_message = curl_strerror($errno);
else
    $error_message = "-";

if (!curl_errno($ch)) {
	$data = json_decode($response, true);
	$policies_array = [];
	if (sizeof($data['items'])>0)
	{
		foreach($data['items'] as $key)
		{
			if($key['type']!="parent")			
			$policies_array[] = ['name' => $key['name'], 'id' => $key['id'], 'type' => $key['type']];
		}
			$final_array = ['error_message' => $error_message,'http_code' => $info['http_code'],'num_of_policies' => sizeof($data['items']), 'items' => $policies_array];
			$input_data = json_encode($policies_array);
	}
	else
	{
			$final_array = ['error_message' => $error_message,'http_code' => $info['http_code'],'num_of_policies' => 0, 'items' => array()];
			$error=1;
	}
}
else
{
			$final_array = ['error_message' => $error_message,'http_code' => $info['http_code'],'num_of_policies' => 0, 'items' => array()];
			$error=2;
}

//header('Content-Type: application/json');
//echo json_encode($final_array);
curl_close($ch);



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>ASM Policy Review </title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.css" rel="stylesheet">
   <!-- Switchery -->
    <link href="vendors/switchery/dist/switchery.min.css" rel="stylesheet">
 
    <!-- Datatables -->
    <link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="additional.css" />	

  </head>
  <body class="nav-sm">
    <div class="container body">
      <div class="main_container">

		<div class="col-md-3 left_col">
		  <div class="left_col scroll-view">
			<!-- sidebar menu -->
			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

			<div class="menu_section active">
				<ul class="nav side-menu" style="">
				  <li><a href="index.php"><img src="images/f5_2.png" height=48px></a>
					
				  </li>
				  
				  <li class="current-page"><a><i class="fa fa-shield"></i> ASM <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu">
					  <?php 
							if($asm_go == 1)
							{
								foreach ($asm as $item)
								{
									echo '<li><a href="asm.php?policy='.$item.'">'.$item.'</a></li>';
								}
							}	
					  ?>
					</ul>
				  </li>
				  <li><a href="report.php"><i class="fa fa-file-text"></i> Report </a>
				  </li>
				  <li><a href="settings.php"><i class="fa fa-cog"></i> Settings</a>
				  </li>
			  </ul>
			  </div>
			</div>
			<!-- /sidebar menu -->

			
		  </div>
		</div>	  
	  
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
		<div class="row">
		<div class="x_title" style="font-size:24px">ASM Policy: </div>
		</div>
		<div class="row">

			  
            <div class="col-md-8 col-sm-8 col-xs-12" id="suggestion_tab">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>ASM Policies</h2> 
                    <ul class="nav navbar-right panel_toolbox">
                    <li><a class="hide filter_icon" id=""><i class="fa fa-filter filter_icon_i"></i></a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
					<div class="clearfix"></div>
                  </div>
                  <div class="x_content">

					<table id="asm_policies" class="table table-striped table-bordered" style="width:100%">
						<thead>
						  <tr>
							<th>Policy Name</th>
							<th style="width: 85px;">Type</th>
							<th style="width: 15px; text-align: center;"></th>
							<th style="width: 15px; text-align: center;"></th>
							
						  </tr>
						</thead>
					 </table>
						<!-- end content  -->

                  </div>
                </div>
				
				<button type="button" class="btn btn-success btn-lg" id="analyze" style="float:right">Analyze</button>
              </div>
 

            <div class="col-md-4 col-sm-4 col-xs-12 hidden" id="status_tab">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Import Status</h2> 
                    <ul class="nav navbar-right panel_toolbox">
                    <li><a class="hide filter_icon" id=""><i class="fa fa-filter filter_icon_i"></i></a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
					<div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<div class="results">
						
					</div>
					  <i class='fa fa-spinner fa-pulse fa-3x del_2'></i>  <h5 class='del_2'> Please wait.. It can take up to 30-40 seconds per policy.</h5> 

                  </div>
                </div>
				
              </div>




 
          </div>

        <!-- /page content -->
     
        
		</div>
        <!-- /footer content -->
      </div>
    </div>


   <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
     <!-- Custom Theme Scripts -->
    <script src="build/js/custom.js"></script>
    <!-- Datatables -->
    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- Switchery -->
    <script src="vendors/switchery/dist/switchery.min.js"></script>
	
<script type="text/javascript">
function tooltip_init () {
  $(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip({
    placement : 'top'
  });
});
}
</script>


<script>

	var asm_policies = <?php echo json_encode($policies_array); ?> ;
	
	$(document).ready(function() {
			var table = $('#asm_policies').DataTable( {
			"data": asm_policies,
			"createdRow": function( row, data, dataIndex ) {
				  $('td', row).eq(2).html("<i class='fa fa-trash fa-2x' ></i>");
			  },
			"columns": [
				{"data": "name" },
				{"data": "type" },
				{ "className": 'delete_button',"data": null},
				{"data": "id" }
				
				],
				"columnDefs": [
				{
				  "targets": [3],
				  "visible": false
				}
				],				
				"autoWidth": false,
				"processing": true,
				"order": [[0, 'desc']]
		} );	

		$('#asm_policies tbody').on( 'click', '.delete_button', function () {
    	   	var idx = table.row(this).index();
    	   	table.row(this).remove().draw( false );  	
    } );

	} );
</script>


<script>

$( "#analyze" ).click(function() {
var table = $('#asm_policies').DataTable();
var payload = "["
var i;
for (i = 0; i < table.rows().count(); i++) { 
	var name = table.cell( i, 0).data();
	var id = table.cell( i, 3).data();
	var type = table.cell( i, 1).data();
	if(i>0)
	{
		payload = payload + ', ';
	}
	
	payload = payload + '{"name":"'+name+'","id":"'+id+'","type":"'+type+'"}';

}
payload = payload + "]"

$("#status_tab").removeClass("hidden");

var policies = JSON.parse(payload);


var i =0;
doLoop(policies);


function doLoop(policies) {
   //exit condition

   if (i >= table.rows().count()) {
	  $(".del_2").remove();
      window.location.replace("index.php");
	  return;
	  
   }
   
 
$(".results").append("<h4>Parsing started for ASM policy<span style='color:blue'><b>: " + policies[i].name + "</b></span></h4>");
  
   //loop body- call ajax
	$.ajax({
	  method: "POST",
	  url: "gather_stats.php",
	  data: { name: policies[i].name, type: policies[i].type, id: policies[i].id }
	})
	.done(function( msg ) {
		$(".results").append("<h4>Parsing completed successfully.</h4>");
		i++;
		doLoop(policies);
		
	  })
	.fail(function( jqXHR, textStatus, Status  ) {
		$(".results").append("<h4>Parsing failed  for policy<span style='color:red'><b>: " + policies[i].name + "</b></span></h4>");
	});
}




});
</script>

  </body>
</html>
