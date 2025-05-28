<?php
$host = "0.tcp.ap.ngrok.io";
$port = "16006";
$sid = "XE";
$username = "C##VARTA";
$password = "Rofi0602"; 



$conn = oci_connect($username, $password, "(DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = $host)(PORT = $port))
    (CONNECT_DATA = (SID = $sid))
)");


if (!$conn) {
    $e = oci_error();
    die("Koneksi gagal: " . $e);
} 
?>