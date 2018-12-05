<?php
//class.User.php

require_once __DIR__."/class.Communicator.php";

class User extends Communicator
{
	public function register($number,$newPassword)
	{
		try
		{
			$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
			$stmt = $this->conn->prepare("INSERT INTO users(userName,userPass)
		                                               VALUES(:number,:password)");
			$stmt->bindparam(":number", $number);
			$stmt->bindparam(":password", $passwordHash);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	
	public function doLogin($userName,$userPass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT userID, userName, userPass FROM users WHERE userName=:userName");
			$stmt->execute(array(':userName'=>$userName));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($userPass, $userRow['userPass']))
				{
					$_SESSION['userSession'] = $userRow['userID'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function isLoggedin()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}
	
	public function redirect($location, $message) {
		header("refresh:2; url='${location}'");
		return $message;
	}
	
	public function redirectToLogin($currentPage)
	{
		header("refresh:1; url='login.php?location=${currentPage}'");
		return "You'll be redirected to login in a second.
		If not, click <a href='login.php?location=${currentPage}'>here</a>.";
	}
	
	public function redirectAfterLogin($location) 
	{
		header("refresh:1; url='${location}'");
		return "You are logged in and will be redirected to your requested page.
		If not, click <a href='${location}'>here</a>.";
	}
	
	public function redirectAfterLogout($location){
		header("refresh:3; url='${location}'");
		return "You are now logged out.";
	}

	public function redirectAfterRegister()
	{
		header("refresh:0; url='login.php?registered'");
	}
	
	public function doLogout()
	{
		session_destroy();
		//unset($_SESSION['userSession']);
		return true;
	}
}
?>