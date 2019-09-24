<?php 
session_start();
require_once 'config.php';
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die('error');


$analyticsQuery = " SELECT * FROM analytics where id=1 ";
$analyticsResult = mysqli_query($conn, $analyticsQuery);
$aAnalyticsRow = mysqli_fetch_assoc($analyticsResult);

if ( (isset($_SESSION['cam_usr']) && !empty($_SESSION['cam_usr'])) && (isset($_SESSION['cam_pass']) && !empty($_SESSION['cam_pass'])) ) {
	if (isset($_POST['update_analytics_id']) && !empty($_POST['update_analytics_id']) && isset($aAnalyticsRow['analytics_id'])) 
	{	

		$query = "UPDATE analytics SET analytics_id='".$_POST['analytics_id']."' WHERE id=".$_POST['id'];
		$result = mysqli_query($conn, $query);

		if (!$result) {
			$add_err = 'Updating failed.';
		}

		$aAnalyticsRow = $_POST;
	}

	// delete button
	if (isset($_GET['logout'])) {
		session_destroy();
		header('Location: dashboard.php');
	}



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

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4 col-lg-4"></div>
				<div class="col-xs-12 col-md-4 col-lg-4">
					<form action="" method="post">
						<input type="hidden" name="id" value="<?php echo isset($aAnalyticsRow['id']) && !empty($aAnalyticsRow['id']) ? $aAnalyticsRow['id'] : ''; ?>"> 
					  <div class="form-group">
					    <label for="email">Analytics id:</label>
					    <input type="text" class="form-control" id="" name="analytics_id" placeholder="Analytics Id" value="<?php echo isset($aAnalyticsRow['analytics_id']) && !empty($aAnalyticsRow['analytics_id']) ? $aAnalyticsRow['analytics_id'] : ''; ?>" required="required">
					  </div>
					  <button type="submit" name="update_analytics_id" class="btn btn-default" value="1">Save</button>
					</form>
				</div>
			</div>
		</div>

	</body>


	<footer>
		<hr>
	    <div class="container">
	        <div class="vcenter">
	           
	                <p>Copyright &copy; version 1.1 <?php echo date('Y') ?> Christian Community Ministries Ltd</p>
	            
	        </div>
	    </div>
	</footer>
</html>
<?php } else { header("Location: dashboard.php"); }?>