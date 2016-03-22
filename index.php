<?php
//Using PDO Connections
function getLevel($text){
	if(empty($text))
		return 0;
	else if(strpos($text ,'*')===false){
		return 1;
	}else{

		return count(explode('*', $text));
	}
	}
}

try{
	//Prepare parameters
	$username ="root";
	$password ="";
	$dbname = "interview"; 
	$host = "127.0.0.1";
	$Conn = new PDO("mysql:host=$host;dbname=$dbname" , $username ,$password);

	//add attributes 
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//User must sent username and password seperated by # symbol for easier processing.
	$sentData = $_REQUEST['text']; 
	$phoneNumber =$_REQUEST['phoneNumber'];
	$level= getLevel($sentData);
	if($level==0)
	{
		$reply = "Menu\n 1.Enter your first and lastname e.g ngugi*samuel\n2.Retrieve details";
		echo "CON ".$reply;
	}else if($level ==1)
	{

		$stmt = $conn->prepare("SELECT  firstname, lastname FROM Users WHERE phone=?");
		$stmt->bindParam(':phone' ,$phoneNumber);
    	$stmt->execute();
    	 $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    	 $message = $result->fetch();

    	 echo "END ".$message['fname'] ." ".$message['lname'];

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
		//Get the data using phone number
		$query ="SELECT * FROM Users WHERE phone=".$phoneNumber;
		$results = $conn->exec($query);

	}
	else
	{
		//explode the text to get the data 
	$explodedData =  explode('*', $sentData);
	//now we have an array 
	$firstname = $explodedData[0];
	$lastname = $explodedData[1];

	//Insert the data to the database
	$query = $conn->prepare("INSERT INTO Users(fname,lname)VALUES(:fname,:lname)");
	$query->bindParam(':fname', $firstname);
	$query->bindParam(':lname',$lastname);

	//Execute the query now !!
	$query->execute();
	//Finish the insert 
	echo "END "."You are registered!";
	}


}catch(PDOExeption $e)
{
	echo "END "."something went wrong!  Try again later";
}

?>