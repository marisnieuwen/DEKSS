<?php
// Check if form is
if (isset($_POST['login-submit'])) {
    // Connection to database
    require 'connection.php';
    //
    $mail = $_POST['mail'];                     // verzonden variabelen invullen
    $password = $_POST['pwd'];

    //check of mail en/of password zijn ingevuld, anders error
    if (empty($mail) || empty($password)) {
        header("Location: ../personeel.php?error=emptyfields");
        exit();
    }
    else {
        $sql = "SELECT * FROM reserveringssysteem.users WHERE uidUsers=? OR emailUsers=?;";
        $statement = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../personeel.php?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($statement, "ss", $mail, $mail);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($password, $row['pwdUsers']);
                if ($pwdCheck == false) {
                    header("Location: ../personeel.php?error=wrongpwd");
                    exit();
                }
                else if ($pwdCheck == true) {
                    session_start();
                    $_SESSION['userId'] = $row['idUsers'];
                    $_SESSION['userUid'] = $row['uidUsers'];

                    header("Location: ../secure.php");
                    exit();
                }
                else {
                    header("Location: ../personeel.php?error=wrongpwd");
                    exit();
                }
            }
            else {
                header("Location: ../personeel.php?error=nouser");
                exit();
            }
        }
    }
}
else {
    header("Location: ../personeel.php");
    exit();
}