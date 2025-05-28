<?php 
    require 'config.php';
    require 'session.php';

    if (isset($_GET['image']) && isset($_SESSION['user_id'])) {
        $stid = oci_parse($conn, 'SELECT IMAGE_PROFILE, IMAGE_TYPE FROM users_tbl WHERE user_id = :user_id');
        oci_bind_by_name($stid, ':user_id', $_SESSION['user_id']);
        oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_LOBS);

        if ($row && $row['IMAGE_PROFILE'] != null && strlen($row['IMAGE_PROFILE']) > 0) {
            header("Content-Type: " . $row['IMAGE_TYPE']);
            echo $row['IMAGE_PROFILE'];
        } else {
            header("Content-Type: image/jpeg");
            readfile('./assets/profile.jpg');
        }
        exit; 
    }

    if($_SESSION['user_id'] ){
        $stid = oci_parse($conn, 'SELECT * FROM users_tbl where user_id = :user_id');
        oci_bind_by_name($stid, ':user_id', $_SESSION['user_id']);
        oci_execute($stid);
        $data = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS);
    } else {
        header('location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href=".\styling\profile.css">
</head>
<body>
    <main class="w-100 p-4">
        <section class="d-flex justify-content-center align-items-center w-100 flex-column">
            <img src="?image=<?php echo $data['USER_ID']?>" class="img-profile" alt="profile user">
            <h1 class="mt-2"><?php echo $data['USERNAME']?></h1>
            <h3 class="mt-2"><?php echo $data['USER_BIO']?></h3>
            <div class="mt-2 d-flex gap-5">
                <h3 class="interaction-text"><a href="edit-profile.php" class="interaction-text text-decoration-none link-dark">Edit profile</a></h3>
                <h3 class="interaction-text"><a href="logout.php" class="interaction-text text-decoration-none link-dark">Logout</a></h3>
            </div>
        </section>    
        <section class="w-100 bar-profile mt-3 border-bottom border-dark border-5 d-flex justify-content-center align-items-center">
            <h2>Dashboard</h2>
            <h2>Galeri</h2>
            <h2>Aktifitas</h2>
        </section>
        <section>

        </section>
    </main>
    <footer></footer>
</body>
</html>