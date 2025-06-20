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
    if (isset($_GET['artwork_id']) && isset($_SESSION['user_id'])) {
        $artwork_id = (int)$_GET['artwork_id'];
        $user_id_from_session = (int)$_SESSION['user_id']; 
        $query_image = "SELECT gambar, tipe_gambar FROM artwork WHERE artwork_id = ". $artwork_id;
        $result_image = mysqli_query($koneksi, $query_image);
        

        if ($result_image) {
            $row_image = mysqli_fetch_assoc($result_image);
            mysqli_free_result($result_image); 
            if ($row_image['gambar'] != null ) {
                header("Content-Type: " . $row_image['tipe_gambar']);
                echo $row_image['gambar']; 
            } 
        } else {
            header("Content-Type: image/jpeg");
            readfile('./assets/profile.jpg');
        }
        exit;
    };

    if(isset($_GET['artwork'])){
        $artwork_id = (int)$_GET['artwork'];
        $query_detail_artwork = "SELECT judul, deskripsi, waktu FROM artwork WHERE artwork_id = ". $artwork_id;
        $result_detail_artwork = mysqli_query($koneksi, $query_detail_artwork);
        $data_detail = mysqli_fetch_assoc($result_detail_artwork);

        $query_get_komen = "SELECT users_tbl.username, komentar.teks FROM komentar JOIN users_tbl ON users_tbl.user_id = komentar.user_id WHERE artwork_id = " .$artwork_id;
        $result_get_komen = mysqli_query($koneksi, $query_get_komen);
        $data_detail_komen = mysqli_fetch_all($result_get_komen, MYSQLI_ASSOC);
    }

    if(isset($_POST['simpan'])){
        $komentar= $_POST['komen'];
        $escaped_komentar = mysqli_escape_string($koneksi, $komentar);
        $query_insert_komen = "INSERT INTO komentar (user_id, artwork_id, teks) VALUES ($user_id_from_session, $artwork_id, '$escaped_komentar')";
        $result_insert_komen = mysqli_query($koneksi, $query_insert_komen);
        header("Location: profile.php?artwork=" . $artwork_id);
        exit;
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
        .container-content-main{
            background-color: #DCD2CF;
            width: 60%;
            border-radius: 10px;
            height: fit-content;
        }
        .container-content-comment{
            background-color: #DCD2CF;
            height: 90%;
            width: 30%;
            border-radius: 10px;
        }
        .content-comment{
            width: 100%;
            max-height: 65%;
            height: auto;
            overflow: scroll;
            &::-webkit-scrollbar {
                width: 0;  
                height: 0; 
                display: none; 
            }
        }
        .form-input-bio[type=text]{
            background-color: #F7ECDC;
            border-radius: 10px;

            height: 60px !important;
            &::-webkit-scrollbar {
                width: 0;  
                height: 0; 
                display: none; 
            }
        }
        .btn-save{
            border-radius: 10px;
            color: white;
            background-color: #55423B;
            padding: 10px 30px;
        }
        .filler{
            height: 130px;
        }
        .img-artwork{
            width: 30%;
            height: auto;
        }
    </style>
</head>
<body class=" vh-100">
    <?php include 'topBar.php'?>
    <main class="px-5 py-3 mt-4 h-75 w-100 gap-5 d-flex h-50">
        <div class="container-content-main p-5 d-flex flex-row gap-3">
            <img src="?artwork_id=<?php echo $artwork_id; ?>" alt="" class="img-artwork">
            <div class="d-flex flex-column justify-content-between">
                <div>
                    <h1><?php echo $data_detail['judul'] ?></h1>
                    <h3><?php echo $data_detail['deskripsi'] ?></h3>
                </div>
                <p><?php echo $data_detail['waktu'] ?></p>
            </div>
        </div>
        <form class="container-content-comment p-3" action="" method="post">
            <h1>Komentar</h1>
            <section class="content-comment">
                <?php 
                    if($data_detail_komen){
                        foreach($data_detail_komen as $komen){
                            ?> 
                            <h3 class="mb-0 mt-1"><?php echo $komen['username']?></h3>
                            <p><?php echo $komen['teks']?></p>
                            <?php
                        }
                    }
                ?>
            </section>
            <textarea type="text" name="komen" id="bio"  placeholder="Masukkan Komentar Anda" class=" form-control h-50 w-100 form-input-bio" style="resize: none;" required></textarea>
            <input type="submit" value="Kirim" name="simpan" class="btn btn-save mt-2">
        </form>
    </main>
    <footer class="filler"></footer>
    <?php include 'navbar.php'?>
</body>
</html>