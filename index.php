<?php
require_once 'config.php';

$dbTable = 'service_buttons';

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die('error');
$setLang = mysqli_query($conn, "SET NAME 'utf8'");

$query = "SELECT * FROM ".$dbTable." WHERE enable = 1 ORDER BY order_number ASC";
$result = mysqli_query($conn, $query);

$analyticsQuery = " SELECT * FROM analytics where id=1 ";
$analyticsResult = mysqli_query($conn, $analyticsQuery);
$aAnalyticsRow = mysqli_fetch_assoc($analyticsResult);
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="https://ccmschools.app/master/style.css"> -->
	<link rel="icon" href="https://ccmschools.app/master/favicon.png">
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-144834900-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', "<?php echo $aAnalyticsRow['analytics_id'] ?>");
	</script>
	<style type="text/css">
		section.top-header{
			margin-top: 25px;
		}
		.staff-box{
			margin-top: 25px;
		}
		.staff .staff-link:hover, .staff .staff-link:active, .staff .staff-link:focus{
			text-decoration: none;
		}
		.staff .staff-image{
			padding: 20px;
			min-height: 165px;
		}

		.staff .card-body{
			min-height: 60px;
		}
		.staff .staff-image img.staff-home-img{
			width: 100%;
		}
		.staff .card .card-text{
			padding: 10px;
			color: #fff;
			text-align: center;
			font-size: 14px;
		}

		@media screen and (max-width: 767px) and (min-width: 0px){
			
			.staff .staff-box{				
	    		margin-bottom: 0px !important;
	    		margin-top: 0px !important;
	    		padding-left: 28px!important;
    			padding-right: 28px!important;
    			padding-top: 20px!important;
			}
		}

	</style>
</head>



<body>

<section class="top-header">
	<header>
		<div class="container">
			<div id="logo" >
				<img src="img/logo.png" alt="icon" class="img img-responsive full-width">
			</div>
		</div>
	</header>
</section>

	<div class="container staff">
		<div class="instructions" style="margin-top: 20px;"><?php echo $text ?><br><br></div>
		
			<?php $cnt =0; while ($row = mysqli_fetch_object($result)) { ?>
				<?php 
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

				<div class="col-xs-12 col-md-2 col-lg-2 staff-box"> 
					<a href="<?=$row->hyperlink?>" class="staff-link">
						<div class="card">
							<div class="staff-image"  style="background-color: <?=$row->first_color?>">
								<img class="card-img-top staff-home-img" src="<?=$row->icon_path?>" alt="Card image cap">
							</div>
						    <div class="card-body" style="background-color: <?=$row->second_color?>">				      
						      <p class="card-text"><?=$row->label?></p>					      
						    </div>
						</div>
					</a>
				</div>
			<?php  } ?>
		
	</div>	


	<?php /* ?>
	<div class="container">
		<div class="instructions"><?php echo $text ?><br><br></div>
		<div class="content row">
			<div id="all_buttons">
				<?php while ($row = mysqli_fetch_object($result)) { ?>
					
						<div class="each_button col-sm-3 col-xs-4 col-md-2" data-id=<?=$row->id?>>
							<div class="col-sm-12">
							    <a href="<?=$row->hyperlink?>">
								<div class="frontpage_square">
									<div class="button-content">
										<div class="icon-area" style="background-color: <?=$row->first_color?>">
											<img src="<?=$row->icon_path?>" alt="icon" class="img img-responsive full-width">
										</div>
								
										<div class="label-area" style="background-color: <?=$row->second_color?>">
											<p><?=$row->label?></p>
										</div>
									</div>
								</div>
								</a>

							</div>
						</div>
					
				<?php } ?>
			</div>
		</div>
	</div>
	<?php */ ?>

	<footer>
		<hr>
	    <div class="container">
	        <div class="vcenter">
	            <div>
	                <p>Copyright &copy; version 1.1 <?php echo date('Y') ?> Christian Community Ministries Ltd | <a href="http://www.ccmschools.edu.au/privacy">Privacy Policy</a></p>
	            </div>
	        </div>
	    </div>
	</footer>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	
</body>


</html>

