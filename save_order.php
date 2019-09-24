<?php
    require_once 'config.php';
    
	if (isset($_POST['ids'])) {
		
		$dbTable = 'service_buttons';

		$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die('error');
		$setLang = mysqli_query($conn, "SET NAME 'utf8'");

        foreach ($_POST['ids'] as $key => $value) {
        	$order_number = $key+1;
			$query = "UPDATE ".$dbTable." SET order_number=".$order_number." WHERE id=".$value;
			mysqli_query($conn, $query);
		}

		$return = array(
			'status' => 1,
			'message' => 'Order Saved'
		);

	} else {
		$return = array(
			'status' => 0,
			'message' => 'Error'
		);
	}
	echo json_encode($return);
	die();

?>