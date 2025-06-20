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

    if($_SESSION['user_id']){
        $user_id_from_session = (int)$_SESSION['user_id']; 
        $query_data = "SELECT * FROM users_tbl WHERE user_id = " . $user_id_from_session;
        $result_data = mysqli_query($koneksi, $query_data);
        $data = mysqli_fetch_assoc($result_data);
        mysqli_free_result($result_data);

        $query_artwork_id = "SELECT * FROM artwork";
        $data_artwork_id  = mysqli_query($koneksi, $query_artwork_id);
        $data_id = mysqli_fetch_all($data_artwork_id, MYSQLI_ASSOC);

        $query_lelang_id = "SELECT * FROM lelang where now() BETWEEN mulai and akhir";
        $data_lelang_id  = mysqli_query($koneksi, $query_lelang_id);
        $data_lelang = mysqli_fetch_all($data_lelang_id, MYSQLI_ASSOC);

        $query_populer_id = "
            SELECT 
                a.artwork_id,
                a.judul,
                a.kategori,
                a.gambar,
                a.tipe_gambar,
                COUNT(k.komentar_id) AS jumlah_komentar
            FROM komentar k
            JOIN artwork a ON k.artwork_id = a.artwork_id
            GROUP BY a.artwork_id, a.judul, a.kategori, a.gambar, a.tipe_gambar
            ORDER BY jumlah_komentar DESC
            LIMIT 10
        ";
        $data_populer_id  = mysqli_query($koneksi, $query_populer_id);
        $data_populer = mysqli_fetch_all($data_populer_id, MYSQLI_ASSOC);
    } else {
        header('location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VARTA BID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .row-container{
            overflow-x: scroll;
            &::-webkit-scrollbar {
                width: 0;  
                height: 0; 
                display: none; 
            }
        }
        .container-content{
            width: 15%;
            min-width: 200px;
            height: fit-content;
            border-radius: 30px;
            background-color: #A67C52;
            text-align: center;
            color: white;
        }
        .img-content{
            width: 100%;
            height: auto;
            border-top-right-radius: 30px;
            border-top-left-radius: 30px;
        }
        .filler{
            height: 130px;
        }

        .container-galeri{
            width: 100%;
            padding-top: 10px;
        }
        .masonry {
            column-count: 4;
            column-gap: 1rem;
        }

        @media (max-width: 992px) {
            .masonry {
                column-count: 3;
            }
        }

        @media (max-width: 661px) {
            .masonry {
                column-count: 2;
            }
        }

        .masonry-item {
            background-color: #A67C52;
            break-inside: avoid;
            margin-bottom: 1rem;
            padding-bottom: 20px;
            color: white;
            text-align: center;
            border-radius: 30px;
        }

        .masonry-item img {
            width: 100%;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }
    </style>
</head>
<body>
    <?php include 'topBar.php'?>
    <main class="px-5 py-3 mt-4 w-100 gap-5">
        <h1>Lelang Tersedia</h1>
        <div class="row-container d-flex gap-4 w-100 py-3">
            <?php 
               if($data_lelang){
                    foreach ($data_lelang as $data_gambar) {
                        ?>
                        <a href="lelang.php?lelang=<?php echo $data_gambar['lelang_id'] ?>" class=" text-decoration-none">
                            <div class="container-content pb-2 shadow">
                                <img src="?lelang_id=<?php echo $data_gambar['lelang_id'] ?>" class="img-content " alt="">
                                <h3 class="my-1"><?php echo $data_gambar['judul'] ?></h3>
                                <p><?php echo $data_gambar['harga'] ?></p> 
                            </div>
                        </a>
                        <?php
                    }
                } 
            ?>
        </div>
        <h1>Seni Terpopuler</h1>
        <div class="row-container d-flex gap-4 w-100 py-3">
            <?php 
               if($data_populer){
                    foreach ($data_populer as $data_gambar) {
                        
                        ?>
                        <a href="artwork.php?artwork=<?php echo $data_gambar['artwork_id'] ?>" class=" text-decoration-none">
                            <div class="container-content pb-2 shadow">
                                <img src="?artwork_id=<?php echo $data_gambar['artwork_id'] ?>" class="img-content " alt="">
                                <h3 class="my-1"><?php echo $data_gambar['judul'] ?></h3>
                            </div>
                        </a>
                        <?php
                    }
                } 
            ?>
        </div>
        <div class="container-galeri py-4">
        <h1 class="mb-4">Pameran Seni</h1>
        <div class="masonry">
            <?php 
               if($data_id){
            
                    foreach ($data_id as $data_gambar) {
                        ?>
                        <a href="artwork.php?artwork=<?php echo $data_gambar['artwork_id'] ?>" class=" text-decoration-none">
                            <div class="masonry-item"><img src="?artwork_id=<?php echo $data_gambar['artwork_id'] ?>" alt="Image 1"><h3 class="mt-2 mb-0"><?php echo $data_gambar['judul'] ?></h3></div>
                        </a>
                        <?php
                    }
                } 
            ?>
        </div>
        </div>
    </main>
    <footer class="filler"></footer>
    <?php 
    include 'navbar.php'
    ?>
</body>
</html>