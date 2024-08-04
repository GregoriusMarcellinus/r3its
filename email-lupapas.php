<!DOCTYPE html>
<html>
    <head>
        <title>Lupa Password</title>
        <link rel="stylesheet" href="CSS/styles.css">
        <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon_io/favicon-32x32.png">
    </head>
    <body>
        <div class="container">
            <div class="border-hijau">
                <div class="card-lupapass">
                    <center><h1 class="luppas">Lupa Password</h1></center>
                    <center><p>Silakan masukkan email anda yang terdaftar</p></center>

                    <form method="post" action="send-password-reset.php">
                        <div class="input-box">
                            <input type="type="email" name="email" id="email" placeholder="Masukkan email"required>
                        </div>
                         <div class="btnl">
                            <button class="btn" >Kirim link reset password</button>
                        </div>
                    </form>
                        <div class="kembali-login">
                            <p>Kembali ke halaman </p><a href="login.php">Login</a>
                        </div>
                </div>
  
            </div>
        </div>
        
        
    </body>
</html>