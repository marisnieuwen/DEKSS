<?php
session_start();

if (!isset($_SESSION['userId'])) {
    // redirect
    header('Location: personeel.php');
    exit;
}
// Maak een array met tijden van 10:00 - 16:30 met stappen van 30 minuten.
$times = [];
$time = strtotime('10:00');
$timeToAdd = 30;

// loop (while of for loop)
while ($time <= strtotime('16:30')) {
    // time toevoegen aan times array
    $times[] = date('H:i', $time);

    // time + een half uur optellen
    $time += 60 * $timeToAdd;
}
//Require database in this file
require_once "includes/connection.php";

//Check if Post isset, else do nothing
if (isset($_POST['submit'])) {
    //Postback with the data showed to the user, first retrieve data from 'Super global'
    $id = mysqli_escape_string($connection, $_POST['id']);
    $new_firstname = mysqli_escape_string($connection, $_POST['new_firstname']);
    $new_lastname = mysqli_escape_string($connection, $_POST['new_lastname']);
    $new_email = mysqli_escape_string($connection, $_POST['new_mail']);
    $new_telnum = mysqli_escape_string($connection, $_POST['new_tel']);
    $new_date = mysqli_escape_string($connection, $_POST['new_date']);
    $new_time = mysqli_escape_string($connection, $_POST['new_time']);
    $new_apptype = mysqli_escape_string($connection, $_POST['new_apptype']);


    //Require the form validation handling
    require_once "includes/form_validation.php";

    //Save variables to array so the form won't break
    //This array is build the same way as the db result
    $afspraak = [
        'new_firstname' => $new_firstname,
        'new_lastname' => $new_lastname,
        'new_mail' => $new_email,
        'new_tel' => $new_telnum,
        'new_date' => $new_date,
        'new_time' => $new_time,
        'new_apptype' => $new_apptype,
    ];

    //Update the record in the database
    $query = "UPDATE reserveringssysteem.appointments
                  SET firstname = '$new_firstname', lastname = '$new_lastname', email = '$new_email', telnum = '$new_telnum', date = '$new_date', start_time = '$new_time',
                    apptype = '$new_apptype'
                  WHERE id = '$id'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        header('Location: secure.php');
        exit;
    } else {
        $errors[] = 'Something went wrong in your database query: ' . mysqli_error($connection);
    }

} else if (isset($_GET['id'])) {
    //Retrieve the GET parameter from the 'Super global'
    $id = $_GET['id'];

    //Get the record from the database result
    $query = "SELECT * FROM reserveringssysteem.appointments WHERE id = " . mysqli_escape_string($connection, $id);
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) == 1) {
        $afspraak = mysqli_fetch_assoc($result);
    } else {
        // redirect when db returns no result
        header('Location: secure.php');
        exit;
    }

} else {
    header('Location: secure.php');
    exit;
}


//Close connection
mysqli_close($connection);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEKSS</title>
    <link rel="stylesheet" type="text/css" id="applicationStylesheet" href="CSS/style.css"/>
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
        <div class="about-content">
            <h1 class="date-text">Afspraak wijzigen
                voor <?= $afspraak['firstname'] . ' ' . $afspraak['lastname'] ?></h1>

            <form action="" method="post">
                <div>
                    <p class="date-text">Reservering voor <?= $afspraak['date'] ?> om <?= $afspraak['start_time'] ?></p>
                </div>
                <div class="data-field">
                    <label for="name"></label>
                    <input class="primary-field" id="new_firstname" type="text" name="new_firstname" placeholder="Voornaam"
                           value="<?= $afspraak['firstname'] ?>"/>
                    <span class="errors"><?= isset($errors['firstname']) ? $errors['firstname'] : '' ?></span>
                </div>
                <div class="data-field">
                    <label for="name"></label>
                    <input class="primary-field" id="new_lastname" type="text" name="new_lastname" placeholder="Achternaam"
                           value="<?= $afspraak['lastname'] ?>"/>
                    <span class="errors"><?= isset($errors['lastname']) ? $errors['lastname'] : '' ?></span>
                </div>
                <div class="data-field">
                    <label for="name"></label>
                    <input class="primary-field" id="new_mail" type="text" name="new_mail" placeholder="E-mail"
                           value="<?= $afspraak['email'] ?>"/>
                    <span class="errors"><?= isset($errors['mail']) ? $errors['mail'] : '' ?></span>
                </div>
                <div class="data-field">
                    <label for="name"></label>
                    <input class="primary-field" id="new_telnum" type="text" name="new_tel" placeholder="Telefoonnummer"
                           value="<?= $afspraak['telnum'] ?>"/>
                    <span class="errors"><?= isset($errors['tel']) ? $errors['tel'] : '' ?></span>
                </div>
                <div class="data-field">
                    <label for="new_apptype">
                        <span class="errors"><?= isset($errors['apptype']) ? $errors['apptype'] : '' ?></span>
                        <select id="new_apptype" name="apptype"><?= isset($new_apptype) ? $new_apptype : '' ?>
                            <option disabled selected value>Soort afspraak</option>
                            <option value="Knippen">Knippen €19,-</option>
                            <option value="Knippen en verven">Knippen en verven €32,50,-</option>
                            <option value="Knippen, wassen, drogen">Knippen, wassen drogen €22,50,-</option>
                            <option value="Totaal">Totaal €35,50</option>
                            <option value="Permanent">Permanent €67,-</option>
                        </select>
                    </label>
                </div>
                <div class="data-field">
                    <input id="new_date" class="primary-field" type="date" name="new_date" value="<?= $afspraak['date'] ?>"/>
                </div>
                <select name="new_time" id="new_time">
                    <option disabled selected value="">Tijd</option>
                    <?php foreach ($times as $time) { ?>
                        <option value="<?= $time ?>"><?= $time ?></option>
                    <?php } ?>
                </select>
                <div class="data-submit">
                    <input type="hidden" name="id" value="<?= $id ?>"/>
                    <input class="data-submit" type="submit" name="submit" value="Wijzigen"/>
                </div>
            </form>
        </div>
    </section>
</div>
</body>
</html>
