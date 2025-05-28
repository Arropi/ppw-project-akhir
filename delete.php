<?php 
    require 'config.php';
    require 'session.php';
    if($_GET['user_id']){
        $user_id = $_GET['user_id'];
        $stid = oci_parse($conn, 'DELETE users_tbl where user_id = :user_id');
        oci_bind_by_name($stid, ':user_id', $user_id);
        oci_execute($stid);
        include_once('logout.php');
    }
?>