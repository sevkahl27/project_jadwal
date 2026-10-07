<?php

$host      = "localhost";
$user      = "root";
$password  = "";
$db        = "sistem_jadwal";
$con       = mysqli_connect($host, $user, $password, $db);
    
if (!$con) {
    die("GAGAL KONEK KE DATABASE: " . mysqli_connect_error());}
?>