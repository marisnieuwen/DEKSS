<?php

//check of gebruiker op de sumbit knop heeft geklikt
if (isset($_POST['signup-submit'])) {

    require "connection.php";

    $username = $_POST['username'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    //check of alle velden zijn ingevuld
    if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
        header("Location: ../signup.php?error=emptyfields&username=" . $username . "&mail=" . $email);
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../signup.php?error=invalidnamemail");
        exit();
    } //Check of er een juist emailadres is ingevuld
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../signup.php?error=invalidmail&username=" . $username);
        exit();
    } //Check of username de juiste tekens bevat
    else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../signup.php?error=invalidname&mail=" . $email);
        exit();

    } //check of de passwords gelijk zijn aan elkaar
    else if ($password !== $passwordRepeat) {
        header("Location: ../signup.php?error=passwordcheck&username=" . $username . "&mail=" . $email);
        exit();
    } //check of username al in gebruik is
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
                $hashedPwd = password_hash($password, PASSWORD_DEFAULT); //hashed het $password
                //voeg de data van het signup formulier toe aan de database
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