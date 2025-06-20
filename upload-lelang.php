<?php 
    require "session.php";
    require "config.php";
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
    <title>Varta Bid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            height: 100vh;
        }
        .upload-file label {
            cursor: pointer !important;
        }

        #upload-file{
            display: none;
        }

        .upload-file{
            width: 100%;
            height: 100%;
            background-color: #D9D9D9;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
        }

        .upload-desc{
            width: 50%;
            height: 100%;
        }

        .form-input-bio[type=text]{
            background-color: #F7ECDC;
            border-radius: 30px;
            height: 100px !important;
            &::-webkit-scrollbar {
                width: 0;  
                height: 0; 
                display: none; 
            }
        }

        .form-input-user{
            background-color: #F7ECDC;
            border-radius: 30px;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .form-input-user[type=number]{
            -moz-appearance: textfield ;
        }
        .btn-save{
            border-radius: 30px;
            color: white;
            background-color: #55423B;
            padding: 10px 30px;
        }

        .btn-back{
            border-radius: 30px;
            color: #6A534A;
            background-color: #EEE9E8;
            padding: 10px 30px;
        }
        .btn-artwork{
            border-radius: 20px;
        }
        .filler{
            height: 130px;
        }
        .container-submit{
            width: 40%;
            height: 110%;
        }
        #img-show{
            display: none;
            width: 100%;
            height: auto;
        }
        @media(max-width: 900px){
            .container-content-form{
                height: 100% !important;
                flex-direction: column-reverse;
            }
            .upload-desc{
                width: 100%;
            }
            .filler{
                height: 200px;
            }
        }
        @media (max-width: 450px){
            body{
                height: fit-content !important;
            }
            .container-submit{
                width: 100% !important;
            }
        }
    </style>
</head>
<body >
    <?php include 'topBar.php'?>
    
    <main class="px-5 py-3 mt-4 h-75 w-100 gap-2">
        <h1>Lelang</h1>
        <form action="" method="post" class="h-50 d-flex w-100 gap-3 container-content-form" enctype="multipart/form-data">
            <div class="d-flex flex-column container-submit">
                <div class="upload-file">
                    <input type="file" id="upload-file" accept="image/*" name="image">
                    <img src="" alt="" id="img-show">
                    <label for="upload-file" class="w-100 h-100 d-flex justify-content-center align-items-center penanda">
                        <div id="information" class="">
                            <img src="./assets/upload.svg" alt="">
                            <h3>Choose File Here</h3>
                        </div>
                    </label>
                </div>
                <div class="mt-2 d-flex flex-column">
                    <?php
                        if(isset($_POST['simpan'])){
                            $file_type = $_FILES['image']['type'];
                            $title =  $_POST['title'];
                            $description = $_POST['description'];
                            $errors = array();
                            $kategori = $_POST['kategori'];
                            $start = new DateTime($_POST['start']) ;
                            $end = new DateTime($_POST['end']);
                            $harga = $_POST['harga'];

                            if($start > $end){
                                $errors[] = "Tanggal Mulai harus lebih awal";
                            }
                            $startFormatted = $start->format('Y-m-d H:i:s');
                            $endFormatted = $end->format('Y-m-d H:i:s');
                            if($harga <= 0){
                                $errors[] = "Harga tidak valid";
                            }
                            if(empty($title)){
                                $errors[] = "Judul tidak boleh kosong";
                            } elseif (strlen($title) > 50){
                                $errors[] = "Judul terlalu panjang";
                            }
                            
                            
                            if (strlen($description) >150 ){
                                $errors[] = "Deskripsi terlalu panjang";
                            }
                            
                            if($_FILES['image']['size']==0){
                                $errors[] = "Gambar diperlukan";
                            }

                            if(empty($errors)){
                                $user_id_from_session = (int)$_SESSION['user_id'];
                                $image_data = null;
                                $image_type = null;

                                $escaped_title = mysqli_real_escape_string($koneksi, $title);
                                $escaped_description = mysqli_real_escape_string($koneksi, $description);
                                $escaped_kategori = mysqli_escape_string($koneksi, $kategori);
                                $escaped_harga = (int) $harga;

                                if($_FILES['image']['size'] > 0){
                                    $image_data = file_get_contents($_FILES['image']['tmp_name']);
                                    $image_type = $_FILES['image']['type'];

                                    $image_data = mysqli_real_escape_string($koneksi, $image_data);
                                    $image_type = mysqli_real_escape_string($koneksi, $image_type);
                                    
                                }
                                $query_insert = "INSERT INTO lelang (user_id, judul, kategori, deskripsi, gambar, tipe_gambar, harga, mulai, akhir) VALUES ($user_id_from_session, '$escaped_title', '$escaped_kategori','$escaped_description', '$image_data', '$image_type', '$escaped_harga', '$startFormatted', '$endFormatted')";
                                $insert_data = mysqli_query($koneksi, $query_insert);
                                header('Location: profile.php');
                                // if($insert_data){
                                //     header('Location: profile.php');
                                //     exit;
                                // }
                            } else {   
                                echo "<div class='alert alert-warning w-100 h-auto p-1 justify-content-center align-items-center ' role='alert'>";
                                echo "<ul>";
                                foreach($errors as $error) {
                                    echo "<li>$error</li>";
                                }
                                echo "</ul>";
                                echo "</div>";
                            }
                        }
                    ?>
                    <div>
                        <input type="submit" value="Publish" name="simpan" class="btn btn-save">
                        <a href="home.php">
                            <input type="button" value="Kembali" class="btn btn-back" href="home.php">
                        </a>
                    </div>
                </div>
            </div>
            <div class="upload-desc d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex">
                        <div class="w-100">
                            <label for="username" class="form-label mt-1"><h2>Title</h2></label>
                            <input type="text" name="title" id="username" class="form-control w-75 form-input-user" placeholder="Masukkan judul lelang" required>
                        </div>
                        <div class="w-100">
                            <label for="harga" class="form-label mt-1"><h2>Harga</h2></label>
                            <input type="number" name="harga" id="harga" class="form-control w-75 form-input-user" placeholder="Masukkan Harga Awal" required>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="w-100">
                            <label for="start" class="form-label mt-1"><h2>Tanggal Mulai</h2></label>
                            <input type="datetime-local" name="start" id="start" class="form-control w-75 form-input-user" required>
                        </div>
                        <div class="w-100">
                            <label for="end" class="form-label mt-1"><h2>Tanggal Akhir</h2></label>
                            <input type="datetime-local" name="end" id="end" class="form-control w-75 form-input-user" required>
                        </div>
                    </div>
                    <label for="bio" class="form-label mt-3"><h2>Description</h2></label>
                    <textarea type="text" name="description" id="bio"  placeholder="Masukkan deskripsi lelang" class=" form-control h-75 w-100 form-input-bio" style="resize: none;"></textarea>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <div class="d-flex justify-content-center align-items-center h-100 w-auto gap-4">
                        <h3 class="m-0">Kategori</h3>
                        <div class="d-flex justify-content-center align-items-center h-100 w-auto gap-2">
                            <input class="form-check-input m-0" type="radio" name="kategori" id="radioDefault1" value="Digital" checked>
                            <label class="form-check-label" for="radioDefault1">
                                Digital
                            </label>
                        </div>
                        <div class="d-flex justify-content-center align-items-center h-100 w-auto gap-2">
                            <input class="form-check-input m-0" type="radio" name="kategori" id="radioDefault2" value="Non Digital">
                            <label class="form-check-label" for="radioDefault2">
                                Non Digital
                            </label>
                        </div>
                        <a href="upload-artwork.php">
                            <input type="button" value="Artwork"  class="btn btn-light btn-artwork shadow" >
                        </a>
                    </div>
                </div>
            </div>
        </form>
        
    </main>
    <footer class="filler"></footer>
    <?php 
    include 'navbar.php'
    ?>
    <script src="upload.js"></script>
</body>
</html>