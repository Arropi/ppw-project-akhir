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

    if($_SESSION['user_id']){
        $stid = oci_parse($conn, 'SELECT * FROM users_tbl where user_id = :user_id');
        oci_bind_by_name($stid, ':user_id', $_SESSION['user_id']);
        oci_execute($stid);
        $data = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
        
    } else {
        header('location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href=".\styling\edit-profile.css">
</head>
<body class="p-5 d-flex justify-content-center align-items-center">
    <script src="https://kit.fontawesome.com/df4d136803.js" crossorigin="anonymous"></script>
    <form class="w-75 " method="post" action="edit-profile.php" enctype="multipart/form-data">
        <div class="w-100 d-flex justify-content-center align-items-center flex-column">
            <h1>Edit Profile</h1>
            <div class="img-container position-relative overflow-hidden">
                <input type="file" id="img-profile" accept="image/*" name="image">
                <label for="img-profile" class="position-absolute d-block h-100 w-100" >
                    <img id="img-show" src="?image=<?php echo $data['USER_ID']?>" alt="Profile Picture" class="img">
                    <div class="overlay position-absolute bottom-0 text-center h-25 w-100">
                        <span><i class="fa-solid fa-camera"></i></span>
                    </div>
                </label>
            </div>
        </div>
        <div class="w-100">
            <label for="username" class="form-label mt-3"><h2>Username</h2></label>
            <input type="text" name="username" value="<?php echo $data['USERNAME'] ?>" id="username" class="form-control w-25 form-input-user" placeholder="Masukkan username baru">
            <label for="bio" class="form-label mt-3"><h2>Bio</h2></label>
            <input type="text" name="bio" id="bio" value="<?php echo $data['USER_BIO'] ?>" placeholder="Masukkan bio baru" class=" form-control h-75 w-75 form-input-bio">
            <?php 
            if(isset($_POST['simpan'])){
                $file_type = $_FILES['image']['type'];
                $username = htmlspecialchars($_POST['username']);
                $bio = htmlspecialchars($_POST['bio']);
                
                $errors = array();
                
                if(empty($username)){
                    $errors[] = "Username tidak boleh kosong";
                } elseif (strlen($username) > 30){
                    $errors[] = "Username terlalu panjang";
                }
                
                
                if (strlen($bio) >90 ){
                    $errors[] = "Bio terlalu panjang";
                }
                $validation = true;
                if($_FILES['image']['size']==0 && $username == $data['USERNAME'] && $bio == $data['USER_BIO']){
                    echo "<div class='alert alert-info mt-3 w-100' role='alert'>";
                    echo "Tidak ada data yang terubah";
                    echo "</div>";
                    $validation = false;
                }

                
                if(empty($errors)) {
                    if($_FILES['image']['size']>0 && $validation){
                        $image = file_get_contents($_FILES['image']['tmp_name']);
                        $stid = oci_parse($conn, 'UPDATE users_tbl SET IMAGE_PROFILE = empty_blob(), IMAGE_TYPE = COALESCE(:image_type, image_type), USERNAME = COALESCE(:username, USERNAME), USER_BIO = COALESCE(:bio, USER_BIO) WHERE user_id =:user_id RETURNING IMAGE_PROFILE INTO :image_blob');
                        $blob = oci_new_descriptor($conn, OCI_D_LOB);

                        oci_bind_by_name($stid, ':user_id', $_SESSION['user_id']);
                        oci_bind_by_name($stid, ':image_blob', $blob, -1, OCI_B_BLOB);
                        oci_bind_by_name($stid, ':image_type', $_FILES['image']['type']);
                        oci_bind_by_name($stid, ':username', $username);
                        oci_bind_by_name($stid, ':bio', $bio);

                        oci_execute($stid, OCI_NO_AUTO_COMMIT);
                        $blob->save($image);
                        oci_commit($conn);
                        header('location: profile.php');
                    } elseif($validation){
                        $stid = oci_parse($conn, 'UPDATE users_tbl SET USERNAME = COALESCE(:username, USERNAME), USER_BIO = COALESCE(:bio, USER_BIO) WHERE user_id = :user_id');
                        oci_bind_by_name($stid, ':user_id', $_SESSION['user_id']);
                        oci_bind_by_name($stid, ':username', $username);
                        oci_bind_by_name($stid, ':bio', $bio);
                        oci_execute($stid);
                        header('location: profile.php');
                    }
                } else {
                    echo "<div class='alert alert-warning w-75 h-auto mt-3' role='alert'>";
                    echo "<ul>";
                    foreach($errors as $error) {
                        echo "<li>$error</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
            };
        ?>
            <div class="alert alert-danger mt-3 d-none flex-column" role="alert" id="alertContainer">
                <h2>Apakah kamu yakin ingin menghapus akun ini?</h2>
                <div>
                    <button type="button" class="btn btn-light" id="close-btn">Tidak</button>
                    <a href="delete.php?user_id=<?php 
                    echo $_SESSION['user_id']
                    ?>">
                        <button type="button" class="btn btn-warning" id="confirmed-btn">Ya</button>
                    </a>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <div class="d-flex gap-5">
                    <input type="submit" value="Simpan" name="simpan" class="btn btn-save">
                    <a href="profile.php">
                        <input type="button" value="Kembali" class="btn btn-back" href="profile.php">
                    </a>
                </div>
                <input type="button" value="Hapus Akun" name="hapus" class="btn btn-danger" id="remove-btn">
            </div>
        </div>
    </form>
    <script src="edit.js"></script>
</body>
</html>