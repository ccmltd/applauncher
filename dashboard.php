<?php session_start();
require_once 'config.php';
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die('error');

$analyticsQuery = " SELECT * FROM analytics where id=1 ";
$analyticsResult = mysqli_query($conn, $analyticsQuery);
$aAnalyticsRow = mysqli_fetch_assoc($analyticsResult);
?>


<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?> Manager</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://ccmschools.app/master/style.css">
	<link rel="icon" href="https://ccmschools.app/master/favicon.png">
	<style type="text/css">
		.icon-area{
			min-height: 145px;
		}
		@media only screen and (min-width: 750px) {
			.add-new{
				margin-top: 20px;
			}				
		}
		@media screen and (max-width: 767px) and (min-width: 0px){
			.each_button{
				padding: 20px !important;
				margin-bottom: 0px !important;
			}
			.add-new{
				margin-bottom: 80px;	
				margin-left: 7px;			
			}
		}
	</style>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-144834900-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', "<?php echo $aAnalyticsRow['analytics_id'] ?>");
	</script>
</head>



<?php

$aryError='';

if (isset($_POST['login'])) {
	if (isset($_POST['usr']) && !empty($_POST['usr']) && !empty($_POST['pwd'])) {
		$userExistsQuery = "Select * from admin WHERE username='".$_POST['usr']."' AND password='".$_POST['pwd']."' ";
		$userExistsresults = mysqli_query($conn, $userExistsQuery);
		if (mysqli_num_rows($userExistsresults) > 0) {
			while ($aUser = mysqli_fetch_assoc($userExistsresults)) {
				$_SESSION['cam_usr'] = $aUser['username'];
				$_SESSION['cam_pass'] =  $aUser['password'];
			}
		} else {
			$aryError = "User Name and password is not matched";
		}		
	}else{

		$aryError = "User Name and password is required fields";
	}
}

if ( (isset($_SESSION['cam_usr']) && !empty($_SESSION['cam_usr'])) && (isset($_SESSION['cam_pass']) && !empty($_SESSION['cam_pass'])) ) {

	
	$dbTable = 'service_buttons';

	
	$setLang = mysqli_query($conn, "SET NAME 'utf8'");

	// add new
	if (isset($_POST['add'])) {
		$enable = 1;
		if (!isset($_POST['enable'])) {
			$enable = 0;
		}

		$sql = "INSERT INTO ".$dbTable." (label, icon_path, first_color, second_color, hyperlink, enable, order_number) 
				VALUES ('".$_POST['label']."', '".$_POST['icon_path']."', '".$_POST['first_color']."', '".$_POST['second_color']."', '".$_POST['hyperlink']."', ".$enable.", ".$_POST['order_number'].")";
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			$add_err = 'Adding failed.';
		}
	}

	// delete button
	if (isset($_GET['delete'])) {
		
		$query = "DELETE FROM ".$dbTable." WHERE id='".$_GET['delete']."'";
		mysqli_query($conn, $query);
		$_SESSION['success_msg'] = 'Deleted successfully';
		header('Location: dashboard.php');
	}

	// delete button
	if (isset($_GET['logout'])) {
		session_destroy();
		header('Location: dashboard.php');
	}

	// update button
	if (isset($_POST['update'])) {
		$enable = 1;
		if (!isset($_POST['enable'])) {
			$enable = 0;
		}

		$query = "UPDATE ".$dbTable." SET label='".$_POST['label']."', icon_path='".$_POST['icon_path']."', first_color='".$_POST['first_color']."', second_color='".$_POST['second_color']."', hyperlink='".$_POST['hyperlink']."', enable=".$enable.", order_number=".$_POST['order_number']." WHERE id=".$_POST['id'];
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$add_err = 'Updating failed.';
		}
	}

	$query = "SELECT * FROM icon_url ORDER BY id DESC";
	$result = mysqli_query($conn, $query);
	$icons = array();
	while ($row = mysqli_fetch_object($result)) {
	  	$icons[] = $row;
	}

	$query = "SELECT * FROM ".$dbTable." ORDER BY order_number ASC";
	$result = mysqli_query($conn, $query);

	?>

	<body>

		<header>
			<div class="container">
				<div class="pull-left col-lg-3 col-xs-6 header-text">
					<h3><?php echo $title ?></h3>
				</div>
				<div class="pull-right col-lg-3 col-xs-6 header-text signout">
					<p><a href="?logout=1">Sign Out</a></p>
				</div>
			</div>
			<hr>
		</header>

		<div class="container">
			<p><?php if (isset($add_err)) { echo $add_err; } ?></p>
<!-- 
			<div class="content row"> -->
				
				


				<p id="order-notice">  </p>
				<!-- <div id="all_buttons"> -->
					<?php
						$cnt =0;
						while ($row = mysqli_fetch_object($result)) { 
						
						$extraClass='';
						if ($cnt==0) {
							echo '<div class="row">';
						}elseif ($cnt%6==0) {
							echo '</div><div class="row">';
						}elseif (mysqli_num_rows($result) != 0 &&  mysqli_num_rows($result)==$cnt) {
							echo '</div>';
						}
						$cnt++;
						
					?>
						<div class="each_button col-md-2 col-xs-12 " data-id="<?=$row->id?>" <?php echo $extraClass; ?> <?php echo  $cnt; ?> >
							<div class="col-xs-6 col-md-6 col-lg-6">
								<a data-toggle="modal" data-target="#editBtn_<?=$row->id?>" >Quick Edit</a>
							</div>
							<div class="col-xs-6 col-md-6 col-lg-6 text-right">
								<a href="?delete=<?=$row->id?>">Delete</a>	
							</div>

							<div class="col-xs-12 col-md-12 col-lg-12">
								<!-- <div class="frontpage_square"> -->
									<div class="button-content <?php if (!$row->enable) { echo "disable-button"; } ?>">
										<div class="icon-area" style="background-color: <?=$row->first_color?>">
											<img src="<?=$row->icon_path?>" alt="icon" class="img img-responsive full-width" style="width: 100%;max-width: 100%; ">
										</div>								
										<div class="label-area" style="background-color: <?=$row->second_color?> ; min-height: 60px;">
											<p><?=$row->label?></p>
										</div>
									</div>
								<!-- </div> -->
							</div>
						
							
							<div class="row">	
							<div class="col-xs-12 col-md-12 col-lg-12">
							  	<!-- Modal -->
							  	<div class="modal fade modal-update" id="editBtn_<?=$row->id?>" role="dialog">
							    	<div class="modal-dialog">
							      		<!-- Modal content-->
							      		<div class="modal-content">
							        		<div class="modal-header">
							          			<button type="button" class="close" data-dismiss="modal">&times;</button>
							          			<h4 class="modal-title">Edit Button</h4>
							        		</div>
									        <form action="" method="post">
									        	<div class="modal-body">
									        		<div class="form-group">
										     			<label for="label">Label:</label>
														<input type="text" name="label" id="label" class="form-control" value="<?=$row->label?>" required>
													</div>

													<div class="form-group">
										     			<label for="icon_path">Icon:</label>
										     			<div class="chosen-icon row">
										     				<div class="col-sm-3 each-icon">
																<img class="img-thumbnail" src="<?=$row->icon_path?>">
																<input type="hidden" name="icon_path" value="<?=$row->icon_path?>">
															</div>
										     			</div>
										     			<button type="button" class="btn btn-default" data-toggle="modal" data-target="#chooseIcon">Choose icon</button>
													</div>

													<div class="form-group">
										     			<label for="first_color">First color:</label>
														<input type="color" name="first_color" id="first_color" class="form-control" value="<?=$row->first_color?>">
													</div>

													<div class="form-group">
										     			<label for="second_color">Second color:</label>
														<input type="color" name="second_color" id="second_color" class="form-control" value="<?=$row->second_color?>">
													</div>

													<div class="form-group">
										     			<label for="hyperlink">Hyperlink:</label>
														<input type="text" name="hyperlink" id="hyperlink" class="form-control" value="<?=$row->hyperlink?>">
													</div>

													<div class="form-group">
										     			<label for="order_number">Order number:</label>
														<input type="number" name="order_number" id="order_number" class="form-control" min="1" value="<?=$row->order_number?>">
													</div>

													<div class="checkbox">
												      	<label><input type="checkbox" name="enable" <?php if ($row->enable) { echo "checked"; } ?>> <strong>Enable</strong></label>
												    </div>

												    <input type="hidden" name="id" value="<?=$row->id?>">
										        </div>
										        <div class="modal-footer">
													<button type="submit" name="update" class="btn btn-primary">Update</button>
										          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										        </div>
									        </form>
							      		</div>
							    	</div>
							  	</div>
							</div>
							</div>
						</div>
					<?php } ?>
				<!-- </div> -->

				<div class="col-sm-2 col-xs-4 add-new" >
					<div class="col-sm-12">
						<div class="frontpage_square">
							<div class="button-content new-button">
								<div class="icon-area">
									<img src="img/icons8-plus-math-96.png" alt="icon" data-toggle="modal" data-target="#newBtn" class="img img-responsive full-width">
								</div>
							</div>
						</div>
					</div>
				</div>
			<!-- </div> -->
	</div>		
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-12">
		  	<!-- Modal -->
		  	<div class="modal fade" id="newBtn" role="dialog">
		    	<div class="modal-dialog">		    
		      		<!-- Modal content-->
		      		<div class="modal-content">
		        		<div class="modal-header">
		          			<button type="button" class="close" data-dismiss="modal">&times;</button>
		          			<h4 class="modal-title">Add New Button</h4>
		        		</div>
				        <form action="" method="post">
				        	<div class="modal-body">
				        		<div class="form-group">
					     			<label for="label">Label:</label>
									<input type="text" name="label" id="label" class="form-control" placeholder="Label" required>
								</div>

								<div class="form-group">
					     			<label for="icon_path">Icon:</label>
					     			<div class="chosen-icon row"></div>
					     			<button type="button" class="btn btn-default" data-toggle="modal" data-target="#chooseIcon">Choose icon</button>
								</div>

								<div class="form-group">
					     			<label for="first_color">First color:</label>
									<input type="color" name="first_color" id="first_color" class="form-control" value="#c05c7f" placeholder="First color">
								</div>

								<div class="form-group">
					     			<label for="second_color">Second color:</label>
									<input type="color" name="second_color" id="second_color" class="form-control" value="#ab4a6b" placeholder="Second color">
								</div>

								<div class="form-group">
					     			<label for="hyperlink">Hyperlink:</label>
									<input type="text" name="hyperlink" id="hyperlink" class="form-control" placeholder="Hyperlink">
								</div>

								<div class="form-group">
					     			<label for="order_number">Order number:</label>
									<input type="number" name="order_number" id="order_number" class="form-control" min="1" value="1">
								</div>

								<div class="checkbox">
							      	<label><input type="checkbox" name="enable"> <strong>Enable</strong></label>
							    </div>

					        </div>
					        <div class="modal-footer">
								<button type="submit" name="add" class="btn btn-primary">Add</button>
					          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					        </div>
				        </form>
		      		</div>		      
		    	</div>
		  	</div>				  	
		</div>
	</div>
</div>


<div class="container">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-12">
		  	<!-- Modal for icon-->
		  	<div class="modal fade" id="chooseIcon" role="dialog">
		    	<div class="modal-dialog">
		    
		      		<!-- Modal content-->
		      		<div class="modal-content">
		        		<div class="modal-header">
		          			<button type="button" class="close" data-dismiss="modal">&times;</button>
		          			<h4 class="modal-title">Icons</h4>
		        		</div>
			        	<div class="modal-body">
			        		<div class="form-group">
								<input type="file" id="new_icon" class="form-control">
								<div class="row" id="add_icon">
									<?php foreach ($icons as $value) { ?>
										<div class="col-xs-3 each-icon">
											<img src="<?=$value->icon_url?>" alt="">
										</div>
									<?php } ?>
								</div>
							</div>
				        </div>
				        <div class="modal-footer">
				        	<button type="button" class="btn btn-primary" data-dismiss="modal" id="use_icon" disabled>Use this</button>
				          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        </div>
		      		</div>
		      
		    	</div>
		  	</div>
	  	</div>
	</div>	
</div>	

		
	</body>


<?php } else { ?>
	<body>
		<div class="login-form">
			<?php if (isset($aryError) && !empty($aryError)): ?>				
				<div class="alert alert-danger">
					<?php echo $aryError; ?>
				</div>
			<?php endif ?>
		    <form action="" method="post">
		        <h3 class="text-center">Dashboard Manager</h2>       
		        <div class="form-group">
		            <input type="text" name="usr" class="form-control" placeholder="Username" required="required">
		        </div>
		        <div class="form-group">
		            <input type="password" name="pwd" class="form-control" placeholder="Password" required="required">
		        </div>
		        <div class="form-group">
		            <button type="submit" name="login" class="btn btn-primary btn-block">Log in</button>
		        </div>
		    </form>
		</div>
	</body>
<?php } ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
	jQuery( function() {

    	jQuery('#all_buttons').sortable({
    		update: function( event, ui ) {
    			send_order();
    		}
    	})


    	jQuery('#all_buttons').disableSelection();


    	function send_order(){
    		var order_id = [];
    		var order_value = [];
    		jQuery('.each_button').each(function(){
    			var id = jQuery(this).data("id");
    			if (typeof id !== 'undefined') {
    				order_id.push(id);
    			}
    		})

    		jQuery.ajax({
		        url: 'save_order.php',
		        dataType: 'text',
		        data: {
	    			ids: order_id
	    		},
		        type: 'post',
		        success: function(php_script_response){
		        	var order_num = 0;
		        	jQuery('.modal-update input[name="order_number"]').each(function(){
		        		order_num = order_num+1;
		        		jQuery(this).val(order_num);
		        	})
		        	response = JSON.parse(php_script_response);
		        	jQuery('#order-notice').text(response.message);
		        	setTimeout(function(){ jQuery('#order-notice').text('  '); }, 3000);
		        }
		    });
    	}


    	jQuery('#new_icon').click(function(){
	        jQuery(this).val('');
	    })


	    jQuery('#new_icon').change(function(){
	        var file_data = $('#new_icon').prop('files')[0];   
		    var form_data = new FormData();                  
		    form_data.append('file', file_data);

		    jQuery.ajax({
		        url: 'upload.php',
		        dataType: 'text',
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: form_data,                         
		        type: 'post',
		        success: function(php_script_response){
		        	response = JSON.parse(php_script_response);
		        	if (response.status) {
		        		var prepend = '<div class="col-xs-3 each-icon">';
						prepend += '<img src="'+response.message+'" alt="">';
						prepend += '</div>';
						jQuery('#add_icon').prepend(prepend);
						jQuery('#new_icon').val('');
		        	} else {
		        		alert(response.message);
		        	}
		        }
		    });
	    })


	    jQuery('#add_icon').on('click', '.each-icon', function(){
			jQuery('.each-icon').not(this).removeClass('selected');
			jQuery(this).addClass('selected');
			jQuery('#use_icon').prop('disabled', false);
		})


		jQuery('#use_icon').click(function(){
			if (jQuery('.modal-body .each-icon.selected').length > 0) {
				jQuery('.modal.in .chosen-icon').empty();
				var img_src = jQuery('.modal-body .each-icon.selected').find('img').attr('src');
				var append = '<div class="col-sm-3 each-icon">';
				append += '<img class="img-thumbnail" src="'+ img_src +'">';
				append += '<input type="hidden" name="icon_path" value="'+ img_src +'">';
				append += '</div>';
				jQuery('.modal.in .chosen-icon').append(append);
			}
		})


		jQuery(document).on('hidden.bs.modal', '.modal', function () {
		    jQuery('.modal:visible').length && jQuery(document.body).addClass('modal-open');
		});

  	});
</script>

	<footer>
		<hr>
	    <div class="container">
	        <div class="vcenter">
	           
	                <p>Copyright &copy; version 1.1 <?php echo date('Y') ?> Christian Community Ministries Ltd</p>
	            
	        </div>
	    </div>
	</footer>
</html>

