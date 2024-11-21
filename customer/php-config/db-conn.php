<?php 
    $host = 'localhost';
    $username = 'root';
    $psw = '';
<<<<<<< HEAD
    $db_name = 'shoplex4_db';
=======
    $db_name = 'shoplex_db';
>>>>>>> safras3
    $conn = mysqli_connect($host, $username, $psw, $db_name);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    
?>