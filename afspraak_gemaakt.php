<?php
// Dogapi variable
$api = "https://dog.ceo/api/breeds/image/random";
// Starts curl client
$curl = curl_init();
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $api);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($curl, CURLOPT_VERBOSE, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// Executes the request
$curlResult = curl_exec($curl);

curl_close($curl);
// Decode the response from json to php
$curlData = json_decode($curlResult);


//Require database in this file
require_once "includes/connection.php";
//Check if Post isset, else do nothing
if (isset($_POST['submit'])) {
//Postback with the data showed to the user, first retrieve data from 'Super global'
    $firstname = mysqli_escape_string($connection, $_POST['firstname']);
    $lastname = mysqli_escape_string($connection, $_POST['lastname']);
    $email = mysqli_escape_string($connection, $_POST['mail']);
    $telnum = mysqli_escape_string($connection, $_POST['tel']);
    $date = mysqli_escape_string($connection, $_POST['date']);
    $time = mysqli_escape_string($connection, $_POST['time']);
    $apptype = mysqli_escape_string($connection, $_POST['apptype']);

//Save variables to array so the form won't break
    $afspraak = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'mail' => $email,
        'tel' => $telnum,
        'date' => $date,
        'time' => $time,
        'apptype' => $apptype,
    ];
}
//Close connection
mysqli_close($connection);
?>

<!doctype html>
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
            <h1 class="date-text">De afspraak is gemaakt op <?= $afspraak['date'] ?> om <?= $afspraak['time'] ?>!</h1>
            <p class="success">Wij wensen u een fijne dag toe en geniet van deze leuke foto van een hond!</p>
            <div class="dogapi">
                <img id="dogapi" src="<?= $curlData->message ?>"
            </div>
        </div>
    </section>
</div>
</body>
</html>
