<div class="nav-bar">
    <div class="nav-inner">

        <div class="nav-left">
            <a href="start.php" class="logo-text">
                GalaExtremist
            </a>
        </div>

        <!-- center navigation links -->
        <div class="nav-center">
            <a href="trips.php">Trips</a>
            <a href="aboutus.php">About Us</a>
            <a href="contactus.php">Contact Us</a>
        </div>

        <div class="nav-right">
            <?php
            // check if userid exists
            // using @ to suppress warning if it doesn't exist yet
            $uid = "";
            if (@$_SESSION["userid"] != "") {
                $uid = $_SESSION["userid"];
            }

            // set default username to user if not found
            $uname = "User";
            if (@$_SESSION["username"] != "") {
                $uname = $_SESSION["username"];
            }

            if ($uid != "") {
            ?>
                <div class="dropdown">
                    <a class="nav-btn dropbtn">Hello, <?php echo htmlspecialchars($uname); ?></a>
                    <div class="dropdown-content">
                        <a href="MyBookings.php">My Bookings</a>
                        <a href="../otherreqs/logout.php">Logout</a>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <a href="login.php" class="nav-btn">Login</a>
                <a href="register.php" class="nav-btn">Register</a>
            <?php
            }
            ?>
        </div>

    </div>
</div>
