<?php

$upload_error=-1;
if(isset($_GET['error']) && $_GET['error']!= '' )
{
    $upload_error= $_GET['error'];
}


$asm = [];
$asm_go = 0;
$dir = getcwd() . '/config_files/';
$scan = scandir($dir);

foreach($scan as $file)
{
    if (is_dir($dir.$file) and !($file=="." || $file==".."))
    {
		array_push ($asm, $file);
    }
}

$policies_count = sizeof($asm);
if ($policies_count >0 )
{
	$asm_go = 1;
	$asm_policies = [];

	foreach($asm as $file)
	{
		$error = 0;
		$info = 0;
		$warning = 0;
		$score = 0;
		
		if(file_exists("config_files/".$file."/suggestions.txt"))
		{
			$string = file_get_contents("config_files/".$file."/suggestions.txt");
			$suggestions = json_decode($string, true);
		
			foreach ($suggestions as $key) {
				if ($key['severity'] == 'info')
					$info++;
				if ($key['severity'] == 'warning')
					$warning++;
				if ($key['severity'] == 'error')
					$error++;
				$score = $score + $key['score'];
			}
		}
		else
		{
			$error = 0;
			$info = 0;
			$warning = 0;
			$score = -100;			
		}
		array_push ($asm_policies, '{"name":"'.$file.'", "info":'.$info.', "warning":'.$warning.', "error":'.$error.', "score":'.$score.'}');
	}
}
else 
{
$asm_policies = [];
array_push ($asm_policies, '{"name":"No Files Found", "info":"-", "warning":"-", "error":"-", "score":-100}');
$image_score = '<span class="badge" style="font-size:32px; padding:8px 15px; background-color:#6a6c6d">-</span>';
$final_score = "-";
}


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

    <title>F5 Configuration Review </title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.css" rel="stylesheet">

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
				<ul class="nav side-menu">
				  <li><a href="index.php"><img src="images/f5_2.png" height=48px></a>
					
				  </li>
				  
				  <li><a><i class="fa fa-shield"></i> ASM <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu">
					  <?php 
							if($asm_go == 1)
							{
								foreach ($asm as $item)
								{
									echo '<li><a href="asm.php?policy='.$item.'">'.$item.'</a></li>';
								}
							}
							else
							{
								echo '<li><a>No Policies</a></li>';
							}
					  ?>
					</ul>
				  </li>
				  <li><a href="report.php"><i class="fa fa-file-text"></i> Report <span class="fa fa-chevron-down"></span></a>
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
				<div class="x_title" style="font-size:24px">LTM & ASM Policy</div>
			</div>
			
			<?php
			
				if ($upload_error == 1)
					echo '<div class="alert alert-danger alert-dismissible" role="alert">
					  <strong>File Upload Error!</strong> Please only upload ZIP files.
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>';
				if ($upload_error == 2)
					echo '<div class="alert alert-danger alert-dismissible" role="alert">
					  <strong>File Upload Error!</strong> The size of the uploaded file is too small (<100Bytes). Most likely there is an error
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>';
				if ($upload_error == 3)
					echo '<div class="alert alert-danger alert-dismissible" role="alert">
					  <strong>File Upload Error!</strong> The uploaded files cannot be found. Check the permissions (move_uploaded_file error).
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>';
				if ($upload_error == 4)
					echo '<div class="alert alert-danger alert-dismissible" role="alert">
					  <strong>File Upload Error!</strong> The uploaded files cannot be accessed. Check the permissions (move_uploaded_file error).
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>';					
				if ($upload_error == 0)
					echo '<div class="alert alert-success alert-dismissible" role="alert">
					  <strong>File Upload Success!</strong> The files were uploaded successfully.
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>';


			?>
			<div class="row">
				<div class="col-md-8 col-sm-8 col-xs-12">
					<div class="x_panel">
					  <div class="x_title">
						<h2>Overview</h2> 
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

					 <table id="overall" class="table table-striped table-bordered" style="width:100%; font-size:13px">
						<thead>
						  <tr>
							<th>Configuration Items</th>
							<th style="width: 80px; text-align:center; color:#E74C3C;">Error</th>
							<th style="width: 100px; text-align:center; color: #d6c304;">Warning</th>
							<th style="width: 100px; text-align:center; color: #31708f;">Info</th>
							<th style="width: 100px; text-align:center;">Score</th>
						  </tr>
						</thead>
						<tbody style="text-align: center;">
					
							
							<?php 	
								foreach($asm_policies as $key)
								{	
									$result = json_decode($key, true);	
										$final_score = 100 - (int)$result['score'];

										if ($final_score <60)
										{
											$image_score = '<span class="badge" style="font-size:32px; padding:8px 15px; background-color:red">F</span>';
										}
										if ($final_score >=60 && $final_score <70)
										{
											$image_score = '<span class="badge" style="font-size:32px; padding:8px 15px;background-color:orange ">D</span>';
										}
										if ($final_score >=70 && $final_score <80)
										{
											$image_score = '<span class="badge" style="font-size:32px; padding:8px 15px; background-color:gray;">C</span>';
										}
										if ($final_score >=80 && $final_score <90)
										{
											$image_score = '<span class="badge" style="font-size:32px; padding:8px 15px; background-color:#1D9B1E">B</span>';
										}			
										if ($final_score >=90 && $final_score <=100)
										{
											$image_score = '<span class="badge" style="font-size:32px; padding:8px 15px; background-color:#30CE31">A</span>';
										}
										if ($final_score >100 )
										{
											$image_score = '<span class="badge" style="font-size:32px; padding:8px 15px; background-color:#6a6c6d">-</span>';
										}										
									echo '
								<tr >
									<td style="text-align: left; font-size:15px"><a href="asm.php?policy='.$result['name'].'">ASM - '.$result['name'].'</a></td>
									<td style="color: #E74C3C; font-size:20px; font-weight: bold;">'.$result['error'].'</a></td>
									<td style="color: #d6c304; font-size:20px; font-weight: bold;">'.$result['warning'].'</a></td>
									<td style="color: #31708f; font-size:20px; font-weight: bold;">'.$result['info'].'</a></td>
									<td>'.$image_score.'</td>
								</tr>';
								}
								?>
						</tbody>
					 </table>

							<!-- end content  -->

					  </div>
					</div>
				</div>
			
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="row">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Importing configuration</h2> 
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
						  <div class="x_content" style="text-align:center">
							<form action="import.php" method="post" autocomplete="off" >

								<div class="row">
									<div class="col-md-8 mb-8" style="text-align:left">
									<label>BIGIP IP Address</label>
									<input type="text" class="form-control" name="bigip_ip" placeholder="BIGIP IP Address" required="" required>
									</div>
									<div class="col-md-4 mb-4" style="text-align:left">
										<label>Delete existing data</label>
										<select class="custom-select d-block w-100 form-control" name="delete_policies" required>
										  <option value="yes">Yes</option>
										  <option value="no" selected>No</option>
										</select>
									</div>
								</div>
								<br>								
								<div class="row">
									<div class="col-md-6 mb-6" style="text-align:left">
										<label style="text-align:left">BIGIP Username</label>
										<input type="text" class="form-control" name="user" placeholder="Username" required>
									</div>

									<div class="col-md-6 mb-6" style="text-align:left">
										<label>BIGIP Password</label>
										<input type="password" class="form-control" name="pass" placeholder="Password" required>
									</div>
								</div>						

								<br>	
								<div class="row">
									<div class="col-md-9 mb-9" style="text-align:left">
										<button class="btn btn-success" type="submit"> Import</button>
									</div>
									
								</div>	
								
							</form>
						  </div>
						</div>
					</div>
					
					<div class="row">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Upload new Files</h2> 
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
						  <div class="x_content" style="text-align:left">
							<form action="upload.php" method="post" enctype="multipart/form-data" >
								<h4> Select the zip file with the audit files: </h4>
								<input type="file" name="fileToUpload" id="fileToUpload" >
								<br><br>
								<button class="btn btn-info" type="submit" onclick="return confirm('This will delete the existing audit files')"> Upload new audit files</button>
							</form>
						  </div>
						</div>
					</div>					

					<div class="row">
						<div class="x_panel">
						  <div class="x_title">
							<h2>Delete existing Audit files</h2> 
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
						  <div class="x_content" style="text-align:left">
							<form action="delete_dir.php" method="get">
								<button class="btn btn-danger" id="delete" onclick="return confirm('This will delete the existing audit files')"> Delete</button>			
							</form>
						  </div>
						</div>
					</div>	
					
					
				</div>

			</div>

       <!-- footer content -->
			
			
			
			
			

		</div>
    </div>
</div>



   <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>


    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.js"></script>

    <!-- Datatables -->
    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>


  </body>
</html>

<script>
	$(document).ready(function() {
		var table = $('#overall').DataTable( {
				"autoWidth": false,
				"processing": true,
				"order": [[0, 'desc']]
		} );	
	} );
</script>

