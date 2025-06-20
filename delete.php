<?php 
require 'config.php';
require 'session.php';

if (isset($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id']; 

    
    $stmt = mysqli_prepare($koneksi, "DELETE FROM users_tbl WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: logout.php");
        exit;
    } else {
        echo "Gagal menghapus user: " . mysqli_error($koneksi);
    }

    mysqli_stmt_close($stmt);
}
?>
