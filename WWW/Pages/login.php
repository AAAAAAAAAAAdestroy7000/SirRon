<?php
session_start();

// I used @ here instead of isset or empty to avoid warnings
// in case the session variable has not been created yet.
// This safely defaults userid and username to empty strings.
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}

// These variables are used to display feedback messages
// after redirects from logincheck.php.
$success = @$_GET["success"];
$error = @$_GET["error"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - GalaExtremists</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/WWW/css/styles.css">

<style>
*{font-family:'Poppins', sans-serif;margin:0;padding:0;box-sizing:border-box;}
body{background:#FAFAFA;min-height:100vh;display:flex;flex-direction:column;position:relative;overflow-x:hidden;}

/* Video background */
.bg-video{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:-2;
}

.video-overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(255,255,255,0.75);
    backdrop-filter:blur(8px);
    z-index:-1;
}

/* Override navbar to be transparent and match start.php positioning */
.nav-bar{
    position: absolute !important;
    background:transparent !important;
    box-shadow:none !important;
    border-bottom:none !important;
}

/* Main content area */
.login-container{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:10px 20px 20px 20px;
}

.login-box{
    background:white;
    padding:30px 35px;
    border-radius:20px;
    width:100%;
    max-width:440px;
    box-shadow:0 10px 40px rgba(0,0,0,0.08);
    border:1px solid #F3F4F6;
}

.login-box h1{
    font-size:32px;
    font-weight:800;
    color:#1F2937;
    margin-bottom:8px;
}

.login-box .subtitle{
    color:#6B7280;
    font-size:15px;
    margin-bottom:20px;
}

.form-group{
    margin-bottom:14px;
    text-align:left;
}

.form-group label{
    display:block;
    font-weight:600;
    font-size:14px;
    color:#374151;
    margin-bottom:8px;
}

.form-group input{
    width:100%;
    padding:14px 18px;
    border:1.5px solid #E5E7EB;
    border-radius:12px;
    font-size:15px;
    transition:all 0.2s;
    background:white;
}

.form-group input:focus{
    outline:none;
    border-color:#7C3AED;
    box-shadow:0 0 0 3px rgba(124, 58, 237, 0.1);
}

.submit-btn{
    width:100%;
    padding:15px;
    background:linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%);
    color:white;
    border:none;
    border-radius:12px;
    font-weight:700;
    font-size:16px;
    cursor:pointer;
    margin-top:8px;
    box-shadow:0 4px 16px rgba(124, 58, 237, 0.3);
    transition:all 0.3s;
}

.submit-btn:hover{
    background:linear-gradient(135deg, #6D28D9 0%, #5B21B6 100%);
    transform:translateY(-2px);
    box-shadow:0 6px 20px rgba(124, 58, 237, 0.4);
}

.divider{
    text-align:center;
    margin:28px 0;
    position:relative;
}

.divider::before{
    content:'';
    position:absolute;
    left:0;
    top:50%;
    width:100%;
    height:1px;
    background:#E5E7EB;
}

.divider span{
    background:white;
    padding:0 16px;
    color:#9CA3AF;
    font-size:14px;
    position:relative;
}

.google-btn{
    width:100%;
    padding:14px;
    background:white;
    border:1.5px solid #E5E7EB;
    border-radius:12px;
    font-weight:600;
    font-size:15px;
    cursor:pointer;
    transition:all 0.2s;
    color:#374151;
}

.google-btn:hover{
    background:#F9FAFB;
    border-color:#D1D5DB;
}

.footer-text{
    text-align:center;
    margin-top:24px;
    color:#6B7280;
    font-size:14px;
}

.footer-text a{
    color:#7C3AED;
    text-decoration:none;
    font-weight:600;
}

.footer-text a:hover{
    text-decoration:underline;
}

.alert{
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:14px;
}

.alert-success{
    background:#D1FAE5;
    color:#065F46;
    border:1px solid #6EE7B7;
}

.alert-error{
    background:#FEE2E2;
    color:#991B1B;
    border:1px solid #FCA5A5;
}
</style>
</head>

<body>

<!-- Video background with loop -->
<video id="bgVideo" class="bg-video" autoplay muted>
    <source src="/WWW/videos/1.mp4" type="video/mp4">
</video>
<div class="video-overlay"></div>

<!-- Transparent navbar with centered logo -->
<div class="nav-bar">
    <div class="nav-inner">

        <div class="nav-left">
            <a href="/WWW/Pages/start.php" class="logo-text">GalaExtremist</a>
        </div>

        <!-- center navigation links -->
        <div class="nav-center">
            <a href="trips.php">Trips</a>
            <a href="forums.php">Forums</a>
        </div>

        <!-- empty nav-right to maintain spacing -->
        <div class="nav-right">
        </div>

    </div>
</div>

<!-- Login form -->
<div class="login-container">
    <div class="login-box">
        
        <h1>Welcome back</h1>
        <p class="subtitle">Sign in to continue your journey</p>

        <!-- Success/Error messages -->
        <?php if ($success != "") { ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php } ?>

        <?php if ($error != "") { ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <!-- Login form -->
        <form method="POST" action="/WWW/Inserts/logincheck.php">
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="submit-btn">Sign In</button>

        </form>

        <div class="divider">
            <span>or continue with</span>
        </div>

        <!-- Google Sign-In -->
        <div style="display:flex;justify-content:center;margin-bottom:20px;">
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

        <p class="footer-text">
            Don't have an account? <a href="/WWW/Pages/register.php">Create one</a>
        </p>

        <p class="footer-text" style="margin-top:12px;">
            <a href="/WWW/Pages/start.php" style="color:#6B7280;">← Back to Home</a>
        </p>

    </div>
</div>

<script src="https://accounts.google.com/gsi/client" async defer></script>

<script>
// handleCredentialResponse: receives Google credential and POSTs it to google_login.php
function handleCredentialResponse(response) {

    var token = response.credential;

    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '/WWW/otherreqs/google_login.php';

    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'token';
    input.value = token;
    form.appendChild(input);

    var src = document.createElement('input');
    src.type = 'hidden';
    src.name = 'source';
    src.value = 'login';
    form.appendChild(src);

    document.body.appendChild(form);
    form.submit();
}
</script>

<script>
// video loop logic - cycles through 1.mp4, 2.mp4, etc.
var video = document.getElementById("bgVideo");
var currentVideo = 1;

video.addEventListener("ended", function() {
    // try to load next video
    currentVideo = currentVideo + 1;
    var nextSrc = "/WWW/videos/" + currentVideo + ".mp4";
    
    // create a test to see if next video exists
    var testVideo = document.createElement("video");
    testVideo.src = nextSrc;
    
    testVideo.onerror = function() {
        // if next video doesn't exist, loop back to 1
        currentVideo = 1;
        video.src = "/WWW/videos/1.mp4";
        video.play();
    };
    
    testVideo.onloadeddata = function() {
        // if next video exists, play it
        video.src = nextSrc;
        video.play();
    };
});
</script>

</body>
</html>
