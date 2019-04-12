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
  margin-right: 100px;
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
//not yet done still needs more detail
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
	$output=strval(json_encode($output));
	$out=(array)json_decode($output,true);
	$format="<strong>Competitive Assignment</strong> 2019";
	$format.="<table><th>username</th><th>Score</th>";
	for($i=0;$i<sizeof($out);$i++){
		$username=$out[$i]["student_id"];
		$score=$out[$i]["mark"];
		$format.="<tr>
			<td>
			$username			
			</td>
			<td>
			 $score
			</td>
		</tr>
		";
	}
	
	echo $format."</table>";
?>
	</body>
</html>
	
	 



