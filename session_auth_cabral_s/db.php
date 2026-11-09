<?php
 $hostname = "localhost";
 $username = "root";
 $password = "";
 $dbname = "auth_demo";

 $conn = new mysqli($hostname, $username, $password, $dbname);

 if ($conn) {
   echo "Connection successful";
 }else {
   echo "Connection failed: " . $conn->error;
 }
?>