<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="./styling/index.css">
    
</head>
<body class="d-flex flex-column vw-100 vh-100 align-items-center justify-content-center">
    <div class="d-flex flex-column-reverse flex-md-row container align-items-center justify-content-center container-login w-75 p-0 flex-md-row">
        <form action="register.php" method="post" class=" p-5 w-50 text-white form-container">
            <div class="mb-3">
                <label for="username" class="form-label"><h2>Username</h2></label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><h2>Email address</h2></label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" required>
                <div id="emailHelp" class="form-text text-white">Contoh email valid: example@gmail.com</div>
            </div>
            <div class="mb-3">
                <label for="inputPassword5" class="form-label"><h2>Password</h2></label>
                <input type="password" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock" name="password">
                <div id="passwordHelpBlock" class="form-text text-white">
                Password minimal 8 karakter.
                </div>
            </div>
            <div>
                <button type="submit" name="register" class="btn shadow btn-submit mb-3">Register</button>
                <p>Sudah punya akun? <a href="index.php" class="text-decoration-none link-light">Login</a></p>
            </div>
        </form>
        <img src=".\assets\Logo Varta Bid 2.png" class=" w-50 h-100 img">
    </div>
    <?php 
        include_once("config.php");

        if(isset($_POST['register'])){
            $email = htmlspecialchars($_POST['email']);
            $password =htmlspecialchars($_POST['password']) ;
            $username = htmlspecialchars($_POST['username']);

            $errors = array();

            if(empty($email)){
                $errors[] = "Email tidak boleh kosong";
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors[] = "Format email tidak valid";
            }

            if(empty($password)){
                $errors[] = "Password tidak boleh kosong";
            } elseif (strlen($password) <8 ){
                $errors[] = "Password minimal 8 karakter";
            }

            if(empty($errors)) {
                $stid = oci_parse($conn, "SELECT user_id FROM users_tbl where email='$email'");
                oci_execute($stid);
                $row = oci_fetch_array($stid, OCI_ASSOC);
                
                

                if($row){
                    echo "<div class='alert alert-warning mt-3 w-75' role='alert'>";
                    echo "Email Telah Terdaftar";
                    echo "</div>";
                } else {
                    $hash_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
                    $create_user = oci_parse($conn, 'INSERT INTO users_tbl (username, email, hash_password) VALUES (:username, :email, :hash_password) RETURNING user_id INTO :user_id');

                    $user_id = null;
                    oci_bind_by_name($create_user, ":username", $username);
                    oci_bind_by_name($create_user, ":email", $email);
                    oci_bind_by_name($create_user, ":hash_password", $hash_password);
                    oci_bind_by_name($create_user, ":user_id", $user_id, -1, SQLT_INT);
                    oci_execute($create_user);

                    $e = oci_error($create_user);
                    
                    if($create_user){
                        session_start();
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['login'] = true;
                        header('location: home.php');
                    } else if($e){
                        echo "<div style='padding: 10px; background-color: #d4edda; color:rgb(211, 66, 30); border-radius: 5px; margin-bottom: 15px;'>";
                        echo $e['message'];
                        echo "</div>";
                    }
                }
            }
        };
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>