<?php
require_once "includes/connection.php";

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


if (isset($_POST['submit'])) {
    //Postback with the data showed to the user, first retrieve data from 'Super global'
    $firstname = htmlspecialchars(mysqli_escape_string($connection, $_POST['firstname']), ENT_QUOTES);
    $lastname = htmlspecialchars(mysqli_escape_string($connection, $_POST['lastname']), ENT_QUOTES);
    $email = htmlspecialchars(mysqli_escape_string($connection, $_POST['mail']), ENT_QUOTES);
    $telnum = htmlspecialchars(mysqli_escape_string($connection, $_POST['tel']), ENT_QUOTES);
    $date = htmlspecialchars(mysqli_escape_string($connection, $_POST['date']), ENT_QUOTES);
    $time = htmlspecialchars(mysqli_escape_string($connection, $_POST['time']), ENT_QUOTES);
    $apptype = htmlspecialchars(mysqli_escape_string($connection, $_POST['apptype']), ENT_QUOTES);
    $endTime = date('H:i', strtotime($time . ' 30minutes'));
    //Require the form validation handling
    require_once "includes/form_validation.php";

    if (empty($errors)) {
        //Save the appointment to the database
        $query = "INSERT INTO reserveringssysteem.appointments (firstname, lastname, email, telnum, date, start_time, end_time, apptype) 
                    VALUES ('$firstname', '$lastname', '$email', '$telnum', '$date', '$time', '$endTime', '$apptype')";
        $result = mysqli_query($connection, $query)
        or die('Error: ' . $query);

        if ($result) {
            header('Location: index.php');
        } else {
            $errors[] = 'Something went wrong in your database query: ' . mysqli_error($connection);
        }
    }
}

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $date = mysqli_escape_string($connection, $_GET['date']);

    // Haal de reserveringen uit de database voor een specifieke datum
    $query = "SELECT *
              FROM reserveringssysteem.appointments
              WHERE date = '$date'";

    $result = mysqli_query($connection, $query);

    if ($result) {
        $afspraken = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $afspraken[] = $row;
        }
    }

    // Doorloop alle reserveringen en filter alle tijden die gelijk zijn
    // aan de tijd van een reservering t/m een half uur later.
    // Zet alle overgebleven tijden in de array $availableTimes.
    $availableTimes = [];

    // doorloop alle tijden (van 10:00 - 16:00)
    foreach ($times as $time) {
        $time = strtotime($time);
        $occurs = false;
        // controleer de tijd tegen ALLE reserveringen van die dag
        foreach ($afspraken as $afspraak) {
            $startTime = strtotime($afspraak['start_time']);
            $endTime = strtotime($afspraak['end_time']);
            // ALS de tijd van de begintijd tot de eindtijd van
            // een reservering valt voeg deze tijd ($time) niet
            // toe aan availableTimes
            if ($time >= $startTime &&
                $time < $endTime) {
                $occurs = true;
            }
        }

        if (!$occurs) {
            $availableTimes[] = date('H:i', $time);
        }
    }


} else {
    header('Location: index.php');
}


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
            <h1 class="date-text">Maak een nieuwe afspraak</h1>

            <form action="afspraak_gemaakt.php" method="post">
                <div>
                    <p class="date-text">Afspraak voor <?= date('d-m-Y', strtotime($date)) ?></p>
                </div>
                <div class="data-field">
                    <label for="firstname"></label>
                    <input class="primary-field" id="firstname" type="text" name="firstname" placeholder="Voornaam"
                           value="<?= (isset($firstname) ? $firstname : ''); ?>"/>
                    <span class="errors"><?= isset($errors['firstname']) ? $errors['firstname'] : '' ?></span>
                </div>
                <div class="data-field">
                    <label for="lastname"></label>
                    <input class="primary-field" id="lastname" type="text" name="lastname" placeholder="Achternaam"
                           value="<?= (isset($lastname) ? $lastname : ''); ?>"/>
                    <span class="errors"><?= isset($errors['lastname']) ? $errors['lastname'] : '' ?></span>
                </div>
                <div class="data-field">
                    <label for="mail"></label>
                    <input class="primary-field" id="mail" type="text" name="mail" placeholder="E-mail"
                           value="<?= (isset($email) ? $email : ''); ?>"/>
                    <span class="errors"><?= isset($errors['mail']) ? $errors['mail'] : '' ?></span>
                </div>
                <div class="data-field">
                    <label for="telnum"></label>
                    <input class="primary-field" id="telnum" type="text" name="tel" placeholder="Telefoonnummer"
                           value="<?= (isset($telnum) ? $telnum : ''); ?>"/>
                    <span class="errors"><?= isset($errors['tel']) ? $errors['tel'] : '' ?></span>
                </div>
                <div class="data-field">
                    <label for="apptype">
                        <span class="errors"><?= isset($errors['apptype']) ? $errors['apptype'] : '' ?></span>
                        <select id="apptype" name="apptype"><?= isset($apptype) ? $apptype : '' ?>
                            <option disabled selected value="">Soort afspraak</option>
                            <option value="Knippen">Knippen €19,-</option>
                            <option value="Knippen en verven">Knippen en verven €32,50,-</option>
                            <option value="Knippen, wassen, drogen">Knippen, wassen drogen €22,50,-</option>
                            <option value="Totaal">Totaal €35,50</option>
                            <option value="Permanent">Permanent €67,-</option>
                        </select>
                    </label>
                </div>
                <div class="data-field">
                    <label for="time"></label>
                    <select name="time" id="time">
                        <option disabled selected value="">Tijd</option>
                        <?php foreach ($availableTimes as $time) { ?>
                            <option value="<?= $time ?>"><?= $time ?></option>
                        <?php } ?>
                    </select>
                    <span class="errors"><?= isset($errors['time']) ? $errors['time'] : '' ?></span>
                </div>
                <input type="hidden" name="date" value="<?= $date ?>"/>
                <div class="data-submit">
                    <input class="data-submit" type="submit" name="submit" value="Afspraak maken"/>
                </div>
            </form>

            </form>
        </div>
    </section>
</div>
</body>
</html>
