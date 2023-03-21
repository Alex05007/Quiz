<?php
$hostname = 'localhost:3307';
$username = "root";
$password = "gNetDB1qaz?1qaz";
try {
    $conn = new PDO("mysql:host=$hostname;dbname=quiz", $username, $password);
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }
?>