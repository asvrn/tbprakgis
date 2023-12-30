<?php
$host= "localhost";
$user= "postgres";
$pass= "12345678";
$port= "5432";
$dbname= "db_prakgis";
$conn= pg_connect("host=".$host." port=".$port."dbname=".$dbname." user=". $user." password=".$pass) or
die("Gagal");
if($conn)
{
    echo "Koneksi Sukses";
}else{
    echo "Koneksi Gagal";
}
?>