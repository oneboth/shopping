<?php
	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
 
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
 
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
 
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
 
        exit(0);
    }
 
   $postdata = file_get_contents("php://input");
  if (isset($postdata)) {
		$request = json_decode($postdata);
		
		$name=$request->n;  
		$username=$request->un; 		
		$password=$request->ps; 
		$password=md5($password);
		$phone=$request->ph; 
		
		$conn = new mysqli("localhost", "root", "both", "users"); // ใส่ server new database
		
		// To protect MySQL injection for Security purpose
		$name = stripslashes($name);
		$username = stripslashes($username);
		$password = stripslashes($password);
		$phone = stripslashes($phone);
		
		
		$name = $conn->real_escape_string($name);
		$username = $conn->real_escape_string($username);
		$password = $conn->real_escape_string($password);
		$phone = $conn->real_escape_string($phone);
		
		
		$check="SELECT count(*) FROM users WHERE u_id = '$username'";
		$rs = mysqli_query($conn,$check);
		$data = mysqli_fetch_array($rs, MYSQLI_NUM);
		//print_r($data);
		if($data[0] > 0) {
			$outp='{"result":{"created": "0" , "exists": "1" } }';
		}
		else{	
			$sql = "INSERT INTO users VALUES ('$name', '$username', '$password', '$phone',1 )";		
			if ($conn->query($sql) === TRUE) {
				$outp='{"result":{"created": "1", "exists": "0" } }';
			} 
		}
		
		echo $outp;
		
		$conn->close();	
	
}
?> 
