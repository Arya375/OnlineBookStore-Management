<?php
$servername="localhost:3307";
$username="root";
$password="";
$db="bms";
$conn=mysqli_connect($servername,$username,$password,$db);
if($conn){
    echo " ";
}
else{
    echo "fail";
}
?>