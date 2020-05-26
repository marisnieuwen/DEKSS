<?php
session_start();

if (!isset($_SESSION['userId'])) {
    // redirect
    header('Location: personeel.php');
    exit;
}
//Require data helpers to use variable in this file
require_once "includes/connection.php";

if (isset($_POST['submit'])) {
    // Get the reservation from the database result
    $query = "SELECT * FROM reserveringssysteem.appointments WHERE id = " . mysqli_escape_string($connection, $_POST['id']);
    $result = mysqli_query($connection, $query) or die ('Error: ' . $query);

    $afspraak = mysqli_fetch_assoc($result);


    // DELETE DATA
    // Remove the reservation from the database
    $query = "DELETE FROM reserveringssysteem.appointments WHERE id = " . mysqli_escape_string($connection, $_POST['id']);

    mysqli_query($connection, $query) or die ('Error: ' . mysqli_error($connection));

    //Close connection
    mysqli_close($connection);

    //Redirect to homepage after deletion & exit script
    header("Location: secure.php");
    exit;

} else if (isset($_GET['id'])) {
    //Retrieve the GET parameter from the 'Super global'
    $afspraakId = $_GET['id'];

    //Get the record from the database result
    $query = "SELECT * FROM reserveringssysteem.appointments WHERE id = " . mysqli_escape_string($connection, $afspraakId);
    $result = mysqli_query($connection, $query) or die ('Error: ' . $query);

    if (mysqli_num_rows($result) == 1) {
        $afspraak = mysqli_fetch_assoc($result);
    } else {
        // redirect when db returns no result
        header('Location: index.php');
        exit;
    }
} else {
    // Id was not present in the url OR the form was not submitted
    // redirect to secure.php
    header('Location: secure.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" id="applicationStylesheet" href="CSS/stylesecure.css"/>
    <title>Delete - <?= $afspraak['firstname'] ?></title>
</head>
<body>
<header>
    <div class="intro">
        <h1 style="font-size: 10vw">
            <a href="index.php">DEKSS</a>
        </h1>
        <nav>
            <ul>
                <li><a href="index.php">Afspraak maken</a></li>
                <li><a href="prijzenlijst.php"> Prijzenlijst</a></li>
                <li><a href="over.php">Over</a></li>
                <li><a href="personeel.php">Personeel</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="wrapper">
    <section class="about">
        <sec class="about-content">
            <h2>Delete - <?= $afspraak['firstname'] ?></h2>
            <form action="" method="post">
                <p>
                    Weet u zeker dat u de afspraak voor <?= $afspraak['firstname'] ?> <?= $afspraak['lastname'] ?>
                    op <?= $afspraak['date'] ?> om <?= $afspraak['start_time'] ?> wilt verwijderen?
                </p>
                <input type="hidden" name="id" value="<?= $afspraak['id'] ?>"/>
                <input type="submit" name="submit" value="Verwijderen"/>
            </form>
        </sec>
    </section>
</div>
</body>
</html>
