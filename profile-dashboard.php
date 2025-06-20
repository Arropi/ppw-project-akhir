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
        $query_artwork_id = "SELECT lelang_id FROM lelang WHERE user_id = ". $user_id_from_session;
        $data_artwork_id  = mysqli_query($koneksi, $query_artwork_id);
        $data_id = mysqli_fetch_all($data_artwork_id, MYSQLI_ASSOC);

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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .pagination .page-link {
            color: #7E5A50; 
            background-color: #F7ECDC; 
            border-color: #F7ECDC; 
        }

        .pagination .page-item.disabled .page-link {
            background-color: #F7ECDC;
            border-color: #F7ECDC;
            pointer-events: none;
        }

        .pagination .page-item .page-link.active {
            z-index: 3; 
            color: #F4F1EF; 
            background-color: #7E5A50 !important; 
            border-color: #F7ECDC; 
            border-top: none !important;    
        }

        .custom-search-input {
            background-color: #D2B48C; 
            border: none; 
            padding-right: 40px; 
            box-sizing: border-box; 
            color: #5C4033; 
            border-radius: 30px;
            padding: 10px;
        }

        .custom-search-button {
            background: none; 
            border: none; 
            color: #5C4033; 
            position: absolute; 
            right: 10px; 
            top: 50%; 
            transform: translateY(-50%); 
            z-index: 2; 
            cursor: pointer; 
            
        }
        .filler{
            height: 130px;
        }
        .navigator{
            text-decoration: none;
            color: black;
        }
        .img-art{
            width: 20%;
            height: 300px;
            object-fit: cover;
        }
        .content{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
            height: fit-content;
            gap: 10px;
        }
    </style>
</head>
<body>
    <script src="https://kit.fontawesome.com/df4d136803.js" crossorigin="anonymous"></script>
    <main class="w-100 p-4">
        <section class="d-flex justify-content-center align-items-center w-100 flex-column">
            <img src="?image=<?php echo $data['user_id']?>" class="img-profile" alt="profile user">
            <h1 class="mt-2"><?php echo $data['username']?></h1>
            <h3 class="mt-2"><?php echo $data['user_bio']?></h3>
            <div class="mt-2 d-flex gap-5">
                <h3 class="interaction-text"><a href="edit-profile.php" class="interaction-text-edit text-decoration-none link-dark">Edit profile</a></h3>
                <h3 class="interaction-text"><a href="logout.php" class="interaction-text-logout text-decoration-none link-dark">Logout</a></h3>
            </div>
            
        </section>    
        <section class="w-100 bar-profile mt-3 border-bottom border-dark border-5 d-flex justify-content-center align-items-center">
            <div class="navigator active" tampilan="dashboard" >
                <h2>Dashboard</h2>
            </div>
            <a href="profile.php" class="text-decoration-none">
                <div class="navigator " tampilan="galeri" >
                    <h2>Galeri</h2>
                </div>
            </a>
            <a href="profile-aktivitas.php" class="text-decoration-none">
                <div class="navigator" tampilan="aktifitas">
                    <h2>Aktifitas</h2>
                </div>
            </a>
        </section>
        
        <section class='bar-profile'>
            <div class="dashboard-content mt-4" id="dashboard">
                <section class="d-flex gap-4">
                    <?php
                    ?>
                    <div class="info d-flex justify-content-between p-3 flex-column text-white">
                        <h2>Pelelangan</h2>
                        <h1><?php echo count($data_id) ?></h1>
                    </div>
                    </div>
                </section>
            </div>
            <div class="content mt-3">
                <?php 
                    if($data_id){
                        foreach ($data_id as $data_gambar) {
                            ?>
                            <a href="lelang.php?lelang=<?php echo $data_gambar['lelang_id'] ?>" class=" text-decoration-none container-ayam">
                                <img src="?lelang_id=<?php echo $data_gambar['lelang_id'] ?>" class=" w-100 img-art shadow" alt=""> 
                            </a>
                            <?php
                        }
                    }
                ?>
            </div>
        </section>
    </main>
    <footer class="filler"></footer>
    <?php 
    include 'navbar.php'
    ?>
</body>
</html>