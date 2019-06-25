<?php

header("Access-Control-Allow-Origin: *");
require_once('../common/conn.php');


//echo json_encode($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method){
	case 'GET':
		getPosts();
		break;
	case 'POST':
		createNewPost();
		break;
	case 'DELETE':
		deletePost();
		break;	
	default:
			// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;	

}


function getPosts(){
	global $conn;
	$pid = null;
	try{
		if(isset($_GET['post_id']))
			$pid = $_GET['post_id'];
		if($pid){
			$query = "SELECT * FROM users WHERE id = {$pid}";
			$res = mysqli_query($conn,$query);

			if($res)	
				echo json_encode($res->fetch_assoc());
			else
				echo "data not found";
		}
		else{
			$query = "SELECT * FROM users";
			$res = mysqli_query($conn, $query);
			if($res){
				$totalPosts = array();
				while($row = mysqli_fetch_assoc($res)){
					$totalPosts[] = $row;
				}

				echo json_encode($totalPosts);
			}
			else{
				echo json_encode("Unable to get the posts");
			}		
		}
	}
	catch(Exception $ex){
		echo json_encode($ex);
	}

}


function deletePost(){
	global $conn;
	$pid = null;
	try{
		if(isset($_GET['post_id']))
			$pid = $_GET['post_id'];
		if($pid){
			$query = "DELETE FROM users WHERE id = {$pid}";
			$res = mysqli_query($conn,$query);
			if($res)	
				echo json_encode("Deleted successfully");
			else
				echo "data not found";
		}
		else{			
				echo json_encode("Provide the post id");
		}
	}
	catch(Exception $ex){
		echo json_encode($ex);
	}
}


function createNewPost(){
	global $conn;
	if(isset($_POST['fname']))
		$fname = $_POST['fname'];
	if(isset($_POST['lname']))
		$lname = $_POST['lname'];
	if(isset($_POST['age']))
		$age = $_POST['age'];
	if(isset($_POST['email']))
		$email = $_POST['email'];

	// echo empty($fname);
	if(empty($fname) || empty($lname) || empty($age) || empty($email)){
		header("HTTP/1.0 404 Bad request");
		exit("Invalid input");
	}
	$query="INSERT INTO users SET name='{$fname}', lastname='{$lname}', age={$age}, email='{$email}'";

	header('Content-Type: application/json');
	
	try{
		$qres = mysqli_query($conn, $query);
		if($qres){
			$insertid = mysqli_insert_id($conn);
			echo json_encode($insertid);
		}
		else{
			echo json_encode(mysqli_error($conn));
		}

	}
	catch(Exception $ex){
		echo json_encode($ex);
	}	
}

//return 

$conn->close();
?>