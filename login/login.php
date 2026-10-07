<?php
include "../koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistem Jadwal | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
     <h1>LOGIN</h1>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <h2 class="text-center mb-4">Selamat Datang</h2>
        <form action="proses-login.php" method="post">
          <label>Username</label>
          <div class="input-group mb-4">
            <input type="email" class="form-control rounded-0" name="username" placeholder="example@gmail.com!" autocomplete="off" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <label>Password</label>
          <div class="input-group mb-3">
            <input type="password" class="form-control rounded-0" id="password" name="password"
             placeholder="Masukkan Password" autocomplete="new-password" required>
            <div class="input-group-append">
              <div class="input-group-text" onclick="lihatPassword()" style="cursor:pointer;">
                <span class="fas fa-eye" id="iconPassword"></span>
              </div>
            </div>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember" required>
            <label class="form-check-label" for="remember">Verifikasi Login</label>
          </div>
          <button type="submit" name="login" class="btn btn-primary btn-block mb-4 rounded-0">LOGIN </button>
        </form>
      </div>
    </div>
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/"></script>
    <script>
      function lihatPassword() {
        var password = document.getElementById("password");
        var icon = document.getElementById("iconPassword");

        if (password.type === "password") {
          password.type = "text";
          icon.classList.remove("fa-eye");
          icon.classList.add("fa-eye-slash");
        } else {
          password.type = "password";
          icon.classList.remove("fa-eye-slash");
          icon.classList.add("fa-eye");
        }
      }
    </script>
</body>
</html>