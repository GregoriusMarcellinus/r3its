<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM tb_pengelolah
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$pengelolah = $result->fetch_assoc();

if ($pengelolah === null) {
    die("token not found");
}

if (strtotime($pengelolah["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Lupa Password</title>
        <link rel="stylesheet" href="CSS/reset-pass.css">
    </head>
    <body>
        <div class="container">
            <div class="border-hijau">
                <div class="card-lupapass">
                    <center><h1 class="respass">Reset Password</h1></center>
                   <div class="peringatan"><center><p>Buatlah kata sandi baru yang tidak Anda gunakan di situs lain</p></center></div> 
                    
                   <form method="post" action="proses-reset-pass.php">

               
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                     
                        <div class="input-box">
                            <input type="password" id="password" name="password"
                             placeholder="kata sandi baru">
                        <div class="input-box">
                            <input type="password" id="password-konfirmasi" name="password-konfirmasi"
                             placeholder="Ulangi kata sandi baru">
                        </div>
            
                        <div class="btnl">
                           <button class="btn">Simpan Perubahan</button>
                       </div>
                    </form>
                </div>
  
            </div>
        </div>
        
    </body>
</html>

