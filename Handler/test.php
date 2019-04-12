<?php

$username = "username";
$password = "password";
$database = "moodle";
	if($link = mysqli_connect("127.0.0.1", $username, $password, $database)){
		$output=array();
		$id = mysqli_real_escape_string($link, $_REQUEST['assign_id']);
		if(!isset($id)){
			die("no assign_id for some reason");
		}
		if($result = mysqli_query($link, "SELECT id,grade FROM mdl_assignfeedback_witsoj WHERE assignment='$id'")){
 			while ($row=$result->fetch_assoc()){
            	$output[]=$row;
        	}
 		}
 		else{
 			die("failed to fetch data");
 		}

	}
	else{
		die("failed to connect to the database");
	}
	mysqli_close($link);
	$output=json_encode($output);

	
	
	 


