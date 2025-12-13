<?php
session_start();
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}
$success = @$_GET["success"];
$error = @$_GET["error"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - GalaExtremists</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family: Arial; }
body, html { height:100%; }
.wrapper {
    position: relative;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
}
video.bg-video {
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:-2;
}
.overlay {
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    z-index:-1;
}
.box {
    background:rgba(0,0,0,0.6);
    padding:40px;
    border-radius:14px;
    width:350px;
    text-align:center;
    border:2px solid #6f42c1;
    backdrop-filter: blur(6px);
    box-shadow:0 0 18px rgba(0,0,0,0.8);
    z-index:2;
}
input {
    width:100%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:8px;
}
.btn-purple {
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#6f42c1;
    color:white;
    font-weight:bold;
    cursor:pointer;
    margin-top:10px;
}
.btn-purple:hover { background:#532e99; }
.google-wrap {
    margin-top:20px;
    display:flex;
    justify-content:center;
}
.back {
    margin-top:20px;
    display:block;
    color:#ddd;
    text-decoration:none;
}
.back:hover { color:white; }
</style>
</head>
<body>
<div class="wrapper">
<video autoplay muted loop class="bg-video">
    <source src="videos/intro.mp4" type="video/mp4">
</video>
<div class="overlay"></div>
<div class="box">
<h2>Login</h2>
<form action="logincheck.php" method="POST">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button class="btn-purple">Login</button>
</form>
<?php
if ($success != "") {
    echo "<p style='color:lightgreen; margin-top:10px;'>" . $success . "</p>";
}
if ($error != "") {
    echo "<p style='color:red; margin-top:10px;'>" . $error . "</p>";
}
?>
<div class="google-wrap">
<div id="g_id_onload"
     data-client_id="397070442652-a36b7hmfeah7sag869fsrgqdcpkvcrs6.apps.googleusercontent.com"
     data-context="signin"
     data-ux_mode="popup"
     data-callback="handleCredentialResponse"
     data-auto_prompt="false">
</div>
<div class="g_id_signin"
     data-type="standard"
     data-shape="rectangular"
     data-theme="outline"
     data-text="signin_with"
     data-size="large">
</div>
</div>
<a href="register.php" class="back">Need an account? Register</a>
<a href="index.php" class="back">Back to Homepage</a>
</div>
</div>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
function handleCredentialResponse(response) {
    var token = response.credential;
    var form = document.createElement("form");
    form.method = "POST";
    form.action = "google_login.php";
    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "token";
    input.value = token;
    form.appendChild(input);
    var src = document.createElement("input");
    src.type = "hidden";
    src.name = "source";
    src.value = "login";
    form.appendChild(src);
    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>
