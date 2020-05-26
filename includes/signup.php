<?php

//Check if form is submitted
if (isset($_POST['signup-submit'])) {

    require "connection.php";

    $username = $_POST['username'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    // Check if no fields are empty
    if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
        header("Location: ../signup.php?error=emptyfields&username=" . $username . "&mail=" . $email);
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../signup.php?error=invalidnamemail");
        exit();
    }
    //Check if the email address is valid
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../signup.php?error=invalidmail&username=" . $username);
        exit();
    }
    //Check if username contains the correct characters
    else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../signup.php?error=invalidname&mail=" . $email);
        exit();
    }
    // Check if the passwords are the same
    else if ($password !== $passwordRepeat) {
        header("Location: ../signup.php?error=passwordcheck&username=" . $username . "&mail=" . $email);
        exit();
    }
    // Check whether username is already in use
    else {
        $sql = "SELECT uidUsers FROM reserveringssysteem.users WHERE uidUsers=?";
        $statement = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../signup.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($statement, "s", $username);
            mysqli_stmt_execute($statement);
            mysqli_stmt_store_result($statement);
            $resultCheck = mysqli_stmt_num_rows($statement);
            if ($resultCheck > 0) {
                header("Location: ../signup.php?error=usertaken&mail=" . $email);
                exit();
            } else {
                $hashedPwd = password_hash($password, PASSWORD_DEFAULT); //hashed the $password
                //Add the data from the sign up form to the database
                $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)";
                $statement = mysqli_stmt_init($connection);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: ../signup.php?error=sqlerror");
                    exit();
                } else {
                    mysqli_stmt_bind_param($statement, "sss", $username, $email, $hashedPwd);
                    mysqli_stmt_execute($statement);
                    header("Location: ../signup.php?error=success");
                    exit();
                }
            }
        }
    }
    mysqli_stmt_close($statement);
    mysqli_close($connection);
} else {
    header("Location: ../signup.php");
    exit();
}