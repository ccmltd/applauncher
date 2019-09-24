<?php
require_once 'config.php';

	if (isset($_FILES['file'])) {
	    if ( 0 < $_FILES['file']['error'] ) {
	    	$return = array(
				'status' => 0,
				'message' => $_FILES['file']['error']
			);
	    } else {
	    	if (file_exists('img/' . $_FILES['file']['name'])) {
	    		$return = array(
					'status' => 0,
					'message' => $_FILES['file']['name']
				);
	    	} else {
	    		
				$dbTable = 'icon_url';

				$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die('error');
				$setLang = mysqli_query($conn, "SET NAME 'utf8'");

		        if (move_uploaded_file($_FILES['file']['tmp_name'], 'img/' . $_FILES['file']['name'])) {
		        	$sql = "INSERT INTO ".$dbTable." (icon_url)	VALUES ('img/".$_FILES['file']['name']."')";
					mysqli_query($conn, $sql);

		        	$return = array(
						'status' => 1,
						'message' => 'img/'.$_FILES['file']['name']
					);
		        } else {
			        $return = array(
						'status' => 0,
						'message' => 'Uploading file failed'
					);
		        }

	    	}
	    }
	} else {
		$return = array(
			'status' => 0,
			'message' => 'Malformed'
		);
	}
	echo json_encode($return);
	die();

?>