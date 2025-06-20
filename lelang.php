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

    if (isset($_GET['lelang_id']) && isset($_SESSION['user_id'])) {
        $lelang_id = (int)$_GET['lelang_id'];
        $user_id_from_session = (int)$_SESSION['user_id']; 
        $query_image = "SELECT gambar, tipe_gambar FROM lelang WHERE lelang_id = ". $lelang_id;
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

    if(isset($_GET['lelang'])){
        $lelang_id = (int)$_GET['lelang'];
        $query_detail_artwork = "SELECT judul, deskripsi, mulai, akhir, harga FROM lelang WHERE lelang_id = ". $lelang_id;
        $result_detail_artwork = mysqli_query($koneksi, $query_detail_artwork);
        $data_detail = mysqli_fetch_assoc($result_detail_artwork);

        $query_get_komen = "SELECT users_tbl.username, penawaran.nominal FROM penawaran JOIN users_tbl ON users_tbl.user_id = penawaran.user_id WHERE lelang_id = " .$lelang_id. " ORDER BY penawaran.nominal DESC";
        $result_get_komen = mysqli_query($koneksi, $query_get_komen);
        $data_detail_komen = mysqli_fetch_all($result_get_komen, MYSQLI_ASSOC);
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
            height: 85%;
            border-radius: 10px;
            overflow: scroll;
            background-color: #F7ECDC;
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
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .form-input-user[type=number]{
            text-align: center;
            border-radius: 0px !important;
            color: white;
            -moz-appearance: textfield ;
        }
        #bid{
            background-color: #8D6E63;
            border: none;
        }
        .button-minus{
            border: none;
            color: white;
            background-color: #8D6E63;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .button-plus{
            border: none;
            color: white;
            background-color: #8D6E63;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .btn-submit{
            padding: 10px 4px;
            border: none;
            background-color: #55423B;
            color: white;
            border-radius: 15px;
        }
        .filler{
            height: 130px;
        }
        @media (max-width: 950px){
            .container-main{
                flex-direction: column;
                height: fit-content;
            }
            .container-content-main{
                width: 100%;
                height: fit-content !important;
            }
            .content-comment{
                height: 70%;
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }
        }
    </style>
</head>
<body class=" vh-100">
    <?php include 'topBar.php'?>
    <main class="px-5 py-3 mt-4 h-75 w-100 gap-5 d-flex h-50 container-main">
        <div class="container-content-main p-5 d-flex flex-row gap-3">
            <form class="d-flex flex-column gap-3" method="post">
                <img src="?lelang_id=<?= $lelang_id ?>" alt="">
                <?php 
                    if(isset($_POST['submit'])){
                        $komentar= $_POST['harga'];
                        $errors = array();
                        if($komentar<=$data_detail_komen[0]['nominal'] ){
                            $errors[] = "Harga tidak boleh lebih kecil dari saat ini";
                        }
                        if(empty($errors)){
                            $escaped_komentar = (int)$komentar; 
                            $query_insert_komen = "CALL tambah_penawaran($user_id_from_session, $lelang_id, $escaped_komentar)";
                            $result_insert_komen = mysqli_query($koneksi, $query_insert_komen);
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
                <div class="d-flex w-100 gap-1">
                    <button type="button" class="button-minus" onclick="kurang()">-</button>
                    <input type="number" class="form-input-user form-control" id="bid" name="harga" value="150000">
                    <button type="button" class="button-plus" onclick="tambah()">+</button>
                </div>
                <button type="submit" name="submit" class="btn-submit"><h3 class="m-0">Ajukan Lelang</h3></button>
            </form>
            <div class="d-flex flex-column">
                <h1><?php echo $data_detail['judul'] ?></h1>
                <h3 class="mb-0"><?php echo $data_detail['deskripsi'] ?></h3>
                <h3 class="mb-0">Tanggal Mulai: <?php echo $data_detail['mulai'] ?></h3>
                <h3 class="mb-0">Tanggal Akhir: <?php echo $data_detail['akhir'] ?></h3>
                <h3 class="mb-0">Harga Awal: <?php echo $data_detail['harga'] ?></h3>
                <h3 class="mb-0">Tawaran Saat Ini: <?php echo $data_detail_komen[0]['nominal']? $data_detail_komen[0]['nominal']: $data_detail['harga'] ?></h3>
            </div>
        </div>
        <div class="container-content-comment p-3">
            <h1>History</h1>
            <section class="content-comment p-3">
                <?php 
                    if($data_detail_komen){
                        foreach($data_detail_komen as $komen){
                            ?> 
                            <h3 class="mb-0 mt-1"><?php echo $komen['username']?></h3>
                            <p><?php echo $komen['nominal']?></p>
                            <?php
                        }
                    }
                ?>
            </section>
        </div>
    </main>
    <footer class="filler"></footer>
    <?php include 'navbar.php'?>
    <script src="lelang.js"></script>
</body>
</html>