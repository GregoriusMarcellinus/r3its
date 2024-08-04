<?php

$email  = $_POST["email"];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s",time()+ 60 * 30);//dipisahkan year month day

$mysqli = require __DIR__ . "/database.php";


$sql =  "UPDATE tb_pengelolah
         SET reset_token_hash = ?,
             reset_token_expires_at = ?
         WHERE email = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

if ($mysqli->affected_rows) {

   $mail = require __DIR__. "/mailer.php";

   $mail->setFrom("noreply@gmail.com");
   $mail->addAddress($email); 
   $mail->Subject = "Password Reset";
   $mail->Body = <<<END

   klik <a href="https://r3its.com/reset-pass.php?token=$token">disini</a> 
   untuk link reset password anda. 

   END;
    
    try{

        $mail->send();

    }catch(Exception $e){
        echo "pesan tidak terkirim. Mailer error: {$mail->ErrorInfo}";
    } 

}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Lupa Password</title>
        <link rel="stylesheet" href="CSS/berhasil.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    </head>
    <body>
        <div class="container">
            <div class="border-hijau">
                <div class="card-lupapass">

                        <i class='bx bx-mail-send bx-fade-right' ></i>
                        <center><h1 class="terkirim">Link atur ulang password berhasil terkirim !</h1></center>
                        <center><p>Silakan cek pesan email anda !</p></center>
        
                        <div class="kembali-login">
                            <p>Kembali ke halaman </p><a href="login.php">Login</a>
                        </div>
                </div>
  
            </div>
        </div>
        
        
    </body>
</html>

