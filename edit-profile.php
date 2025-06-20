<?php 
    require 'config.php';
    require 'session.php';

    if (isset($_GET['image']) && isset($_SESSION['user_id'])) {
        $user_id_from_session = (int)$_SESSION['user_id']; 
        $query_image = "SELECT IMAGE_PROFILE, IMAGE_TYPE FROM users_tbl WHERE user_id = " . $user_id_from_session;
        $result_image = mysqli_query($koneksi, $query_image);
        

        if ($result_image) {
            $row_image = mysqli_fetch_assoc($result_image);
            mysqli_free_result($result_image); 
            if ($row_image['IMAGE_PROFILE'] != null ) {
                header("Content-Type: " . $row_image['IMAGE_TYPE']);
                echo $row_image['IMAGE_PROFILE']; 
            } else {
                header("Content-Type: image/jpeg");
                readfile('./assets/profile.jpg'); 
            }
        } else {
            header("Content-Type: image/jpeg");
            readfile('./assets/profile.jpg');
        }
        exit;
    }

    if($_SESSION['user_id']){
        $user_id_from_session = (int)$_SESSION['user_id']; 
        $query_data = "SELECT * FROM users_tbl WHERE user_id = " . $user_id_from_session;
        $result_data = mysqli_query($koneksi, $query_data);
        $data = mysqli_fetch_assoc($result_data);
        mysqli_free_result($result_data);
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
    <style>
        .filler{
            height: 130px;
        }
    </style>
</head>
<body class="p-5 d-flex justify-content-center align-items-center">
    <script src="https://kit.fontawesome.com/df4d136803.js" crossorigin="anonymous"></script>
    <form class="w-75 " method="post" action="edit-profile.php" enctype="multipart/form-data">
        <div class="w-100 d-flex justify-content-center align-items-center flex-column">
            <h1>Edit Profile</h1>
            <div class="img-container position-relative overflow-hidden">
                <input type="file" id="img-profile" accept="image/*" name="image">
                <label for="img-profile" class="position-absolute d-block h-100 w-100" >
                    <img id="img-show" src="?image=<?php echo $data['user_id']?>" alt="Profile Picture" class="img">
                    <div class="overlay position-absolute bottom-0 text-center h-25 w-100">
                        <span><i class="fa-solid fa-camera"></i></span>
                    </div>
                </label>
            </div>
        </div>
        <div class="w-100">
            <label for="username" class="form-label mt-3"><h2>Username</h2></label>
            <input type="text" name="username" value="<?php echo $data['username'] ?>" id="username" class="form-control w-25 form-input-user" placeholder="Masukkan username baru">
            <label for="bio" class="form-label mt-3"><h2>Bio</h2></label>
            <input type="text" name="bio" id="bio" value="<?php echo $data['user_bio'] ?>" placeholder="Masukkan bio baru" class=" form-control h-75 w-75 form-input-bio">
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

                
                if(empty($errors) && $validation) {
                    $user_id_from_session = (int)$_SESSION['user_id'];
                    $image_data = null;
                    $image_type = null;

                    $escaped_username = mysqli_real_escape_string($koneksi, $username);
                    $escaped_bio = mysqli_real_escape_string($koneksi, $bio);

                    if($_FILES['image']['size'] > 0){
                        $image_data = file_get_contents($_FILES['image']['tmp_name']);
                        $image_type = $_FILES['image']['type'];

                        $image_data = mysqli_real_escape_string($koneksi, $image_data);
                        $image_type = mysqli_real_escape_string($koneksi, $image_type);
                    }

                    $query_update = "UPDATE users_tbl SET 
                                        IMAGE_PROFILE = COALESCE('$image_data', IMAGE_PROFILE), 
                                        IMAGE_TYPE = COALESCE('$image_type', IMAGE_TYPE), 
                                        USERNAME = COALESCE('$escaped_username', USERNAME), 
                                        USER_BIO = COALESCE('$escaped_bio', USER_BIO) 
                                    WHERE user_id = $user_id_from_session";
                    
                    $update_success = mysqli_query($koneksi, $query_update);
                    
                    if($update_success){
                        header('location: profile.php');
                        exit();
                    } else {
                        echo "<div class='alert alert-danger mt-3 w-100' role='alert'>";
                        echo "Gagal memperbarui profil: " . mysqli_error($koneksi);
                        echo "</div>";
                    }
                } elseif (!empty($errors)) { 
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
    <footer class="filler"></footer>
    <?php include 'navbar.php'?>
    <script src="edit.js"></script>
</body>
</html>