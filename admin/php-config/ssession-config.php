<?php

function check_login($conn)
{

	if(isset($_SESSION['user_id']))
	{

		$user_id = $_SESSION['user_id'];
		$query = "SELECT * FROM user WHERE user_id = '$user_id' limit 1";

		$result = mysqli_query($conn,$query);
		if($result && mysqli_num_rows($result) > 0)
		{

			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	header("Location: index.php");
	die;

}

