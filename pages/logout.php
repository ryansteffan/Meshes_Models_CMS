<?php
require("../utilities/auth.php");

// Logs the user out and then redirects them back to the home page.
set_user_logged_out();
header("Location: ../index.php");
