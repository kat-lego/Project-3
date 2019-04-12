<!DOCTYPE html>
<html>
<head>
  <title>Leaderboard</title>
  <link rel="stylesheet" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 50%;
  border: 1px solid #ddd;
}
th, td {
  text-align: left;
  padding: 16px;
}
tr:nth-child(even) {
  background-color: #f2f2f2
}
</style>
</head>
<body>
<?php
$username = "username";
$password = "password";
$database = "moodleDev";
	if($link = mysqli_connect("127.0.0.1", $username, $password, $database)){
		$output=array();
		$id = mysqli_real_escape_string($link, $_REQUEST['assign_id']);
		if(strval($id)==""){
			die("no assign_id for some reason");
		}
		if($result = mysqli_query($link, "SELECT student_id,mark FROM mdl_customfeedback_submission WHERE assign_id='$id'")){
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
	$format="<h1>Competitive Assignment</h1>";
	$format.="<table><th>username</th><th>Score</th>";
	for($i=0;$i<sizeof($output);$i++){
		$format.="<tr>
			<td>
			$output[$i]['student_id']			
			</td>
			<td>
			 $output[$i]['mark']
			</td>
		</tr>
	";
	}
	
	echo $format."</table>";
?>
	</body>
</html>
