<?php
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $mysqli = require __DIR__ ."/database.php";
    $sql = sprintf("SELECT * FROM tb_pengelolah
            WHERE email = '%s'",
            $mysqli->real_escape_string($_POST["email"])); 

    $result = $mysqli->query($sql);
    $pengelolah = $result->fetch_assoc();
    
    if($pengelolah){

       if(password_verify($_POST["password"], $pengelolah["password_hash"])){

        session_start();
            
        session_regenerate_id();

        $_SESSION["pengelolah_id"] = $pengelolah["id_pengelolah"];

        header("location:beranda.php");
        exit;

       }
    }
    $is_invalid = true;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="CSS/Loginstyle.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon_io/favicon-32x32.png">
    </head>
    <body>
        <div class="container">
            <div class="border-hijau">
                <div class="card-login">
                    <h1 class="halo">Halo,</h1>
                    <h1 class="selamat">Selamat datang  kembali !</h1>
                   
                <?php if($is_invalid): ?>
                    <div class="box-error">
                        <div class="pesan-error">
                            <em>Email atau Password tidak tersedia!</em>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="box-error">
                            <div id="idle-login" class="idle-login">
                                <ul>Untuk tetap terhubung, silakan login menggunakan 
                                informasi pribadi Anda</ul>
                            </div>
                    </div>
                <?php endif; ?>

                    <form method="post">

                        <div class="input-box">
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST
                            ["email"] ?? "")?>" 
                             placeholder="Masukkan email pengguna">
                             <i class='bx bx-envelope'></i>
                        </div>

                        <div class="input-box">
                            <input type="password" id="password" name="password" 
                             placeholder="Masukkan kata sandi">
                             <i class='bx bx-lock' ></i>
                        </div>
                        <div class="remember-forgot">
                            <a href="email-lupapas.php">Lupa password?</a>
                        </div>

                        <div class="btnb">
                           <input type="submit" class="btn" name="login" value="Masuk"/>
                       </div>
                    </form>
                </div>
  
            </div>
        </div>
        
        
    </body>
</html>
