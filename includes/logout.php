<?php
// Log out by removing de data from the session and discarding it
session_start();
session_unset();
session_destroy();

header("Location: ../personeel.php"); //Go back to the login page