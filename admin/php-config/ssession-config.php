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

<<<<<<< HEAD
	header("Location: signin_page.php");
=======
	header("Location: index.php");
>>>>>>> safras3
	die;

}

